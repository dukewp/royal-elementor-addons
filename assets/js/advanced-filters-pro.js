// ... existing code ...

function initDependentSelect($scope) {
    var mainSelect = $scope.find('.wpr-main-select-filter');
    var dependentSelects = $scope.find('.wpr-dependent-select');

    // Handle main select change
    mainSelect.on('change', function() {
        var selectedValue = $(this).val();
        var taxonomy = $(this).data('taxonomy');

        // Reset and disable all dependent selects
        dependentSelects.each(function() {
            $(this).prop('disabled', true).empty().append('<option value="">' + $(this).find('option:first').text() + '</option>');
        });

        if (selectedValue) {
            loadDependentOptions(selectedValue, taxonomy, dependentSelects.first());
        }
    });

    // Handle dependent select changes
    dependentSelects.on('change', function() {
        var selectedValue = $(this).val();
        var taxonomy = $(this).data('taxonomy');
        var nextSelect = $(this).nextAll('.wpr-dependent-select').first();

        // Reset and disable all following selects
        $(this).nextAll('.wpr-dependent-select').each(function() {
            $(this).prop('disabled', true).empty().append('<option value="">' + $(this).find('option:first').text() + '</option>');
        });

        if (selectedValue && nextSelect.length) {
            loadDependentOptions(selectedValue, nextSelect.data('taxonomy'), nextSelect);
        }
    });

    function loadDependentOptions(parentTerm, taxonomy, targetSelect) {
        $.ajax({
            url: WprConfig.ajaxurl,
            type: 'POST',
            data: {
                action: 'wpr_get_dependent_terms',
                nonce: WprConfig.nonce,
                taxonomy: taxonomy,
                parent_term: parentTerm
            },
            success: function(response) {
                if (response.success && response.data.length) {
                    var options = '<option value="">' + targetSelect.find('option:first').text() + '</option>';
                    response.data.forEach(function(term) {
                        options += '<option value="' + term.slug + '">' + term.name + '</option>';
                    });
                    targetSelect.html(options).prop('disabled', false);
                }
            }
        });
    }
}

// Initialize dependent select functionality
$scope.find('.wpr-dependent-select-wrap').each(function() {
    initDependentSelect($(this));
});

// ... existing code ...