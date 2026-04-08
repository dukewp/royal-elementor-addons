const fs = require('fs');
const path = require('path');

const sectionsDir = path.join(__dirname, 'sections');
const catalogFile = path.join(__dirname, 'catalog.json');
const catalog = [];

const TEXT_KEYS = [
    'title', 'editor', 'title_text', 'description_text', 'text',
    'prefix_text', 'animated_text', 'heading', 'tab_title', 'tab_content',
    'item_title', 'item_description', 'btn_text', 'button_text',
    'highlighted_text', 'suffix_text',
    'front_title', 'front_description', 'back_title', 'back_description',
    'front_btn_text', 'back_btn_text',
];

function analyzeElement(el, result) {
    if (!el) return;

    if (el.settings) {
        for (const key of TEXT_KEYS) {
            if (el.settings[key] && typeof el.settings[key] === 'string') {
                const clean = el.settings[key].replace(/<[^>]*>/g, '').trim();
                if (clean.length > 2) {
                    result.textFields.push({
                        key: key,
                        value: clean.substring(0, 80),
                        widgetType: el.widgetType || 'section'
                    });
                }
            }
        }

        for (const [sKey, sVal] of Object.entries(el.settings)) {
            if (Array.isArray(sVal) && sVal.length > 0 && typeof sVal[0] === 'object' && sVal[0] !== null) {
                for (const item of sVal) {
                    for (const key of TEXT_KEYS) {
                        if (item[key] && typeof item[key] === 'string') {
                            const clean = item[key].replace(/<[^>]*>/g, '').trim();
                            if (clean.length > 2) {
                                result.textFields.push({
                                    key: 'repeater.' + key,
                                    value: clean.substring(0, 80),
                                    widgetType: el.widgetType || 'repeater'
                                });
                            }
                        }
                    }
                    if (item.image && typeof item.image === 'object' && item.image.url) {
                        result.imageCount++;
                    }
                }
            }
        }

        if (el.settings.background_image && el.settings.background_image.url) {
            result.hasBackgroundImage = true;
            result.imageCount++;
        }
        if (el.settings.image && typeof el.settings.image === 'object' && el.settings.image.url) {
            result.imageCount++;
        }
        if (el.settings.background_video_link) {
            result.hasVideo = true;
        }

        for (const [k, v] of Object.entries(el.settings)) {
            if (typeof v === 'string' && v.match(/^#[0-9a-fA-F]{3,8}$/)) {
                result.colors.add(v.toLowerCase());
            }
        }
    }

    if (el.widgetType) result.widgets.add(el.widgetType);

    if (el.elements && Array.isArray(el.elements)) {
        for (const child of el.elements) {
            analyzeElement(child, result);
        }
    }
}

const categories = fs.readdirSync(sectionsDir).filter(f =>
    fs.statSync(path.join(sectionsDir, f)).isDirectory()
);

for (const cat of categories) {
    const catDir = path.join(sectionsDir, cat);
    const files = fs.readdirSync(catDir).filter(f => f.endsWith('.json'));

    for (const file of files) {
        const data = JSON.parse(fs.readFileSync(path.join(catDir, file), 'utf8'));
        const id = cat + '/' + file.replace('.json', '');

        const result = {
            textFields: [],
            widgets: new Set(),
            colors: new Set(),
            hasBackgroundImage: false,
            hasVideo: false,
            imageCount: 0
        };

        if (data.content) {
            for (const el of data.content) {
                analyzeElement(el, result);
            }
        }

        // Column count
        const columnCount = data.content ? data.content.reduce((acc, el) => {
            return acc + (el.elements ? el.elements.length : 0);
        }, 0) : 0;

        let layout = 'single-column';
        if (columnCount === 2) layout = 'two-column';
        else if (columnCount === 3) layout = 'three-column';
        else if (columnCount > 3) layout = 'multi-column';

        catalog.push({
            id,
            category: cat,
            number: file.replace('.json', ''),
            is_pro: file.includes('pro'),
            is_woo: file.includes('woo'),
            widgets: [...result.widgets],
            text_field_count: result.textFields.length,
            text_fields: result.textFields.map(t => {
                const val = t.value;
                const len = val.length;
                // Auto-detect field type from content
                let type = 'paragraph';
                if (val.match(/^[\(\+\d][\d\s\-\(\)\.]+$/)) type = 'phone';
                else if (val.includes('@') && val.includes('.')) type = 'email';
                else if (val.match(/^https?:\/\//)) type = 'url';
                else if (val.match(/^\d+[\.\,]?\d*\s*[A-Za-z]/)) type = 'address';
                else if (val.endsWith('?')) type = 'question';
                else if (t.key.includes('btn') || t.key.includes('button')) type = 'button';
                else if (len <= 15) type = 'label';
                else if (len <= 40) type = 'heading';
                else if (len <= 80) type = 'subheading';
                return { type, chars: len, sample: val.substring(0, 30) };
            }),
            image_count: result.imageCount,
            has_background_image: result.hasBackgroundImage,
            has_video: result.hasVideo,
            layout: layout,
            description: cat.replace(/-/g, ' ') + ' | ' + layout +
                (result.hasBackgroundImage ? ', bg-image' : '') +
                (result.hasVideo ? ', video' : '') +
                ' | ' + result.textFields.length + ' texts, ' + result.imageCount + ' images'
        });
    }
}

catalog.sort((a, b) => a.category.localeCompare(b.category) || a.id.localeCompare(b.id));
fs.writeFileSync(catalogFile, JSON.stringify(catalog, null, 2));

console.log('Catalog rebuilt: ' + catalog.length + ' sections\n');
for (const cat of [...new Set(catalog.map(c => c.category))]) {
    const items = catalog.filter(c => c.category === cat);
    const avgTexts = (items.reduce((a, c) => a + c.text_field_count, 0) / items.length).toFixed(1);
    const withBgImg = items.filter(c => c.has_background_image).length;
    const withVideo = items.filter(c => c.has_video).length;
    console.log(cat + ': ' + items.length + ' sections, avg ' + avgTexts + ' text fields, ' + withBgImg + ' bg-images, ' + withVideo + ' videos');
}
