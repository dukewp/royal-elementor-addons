(function($) {
    'use strict';

    var WprAiBuilder = {

        selectedStyle: 'modern',

        init: function() {
            this.bindEvents();
            this.toggleModelOptions();
        },

        bindEvents: function() {
            // Settings
            $('#wpr-ai-save-btn').on('click', this.saveSettings.bind(this));
            $('#wpr-ai-test-btn').on('click', this.testConnection.bind(this));
            $('#wpr-ai-provider').on('change', this.toggleModelOptions.bind(this));
            $('#wpr-ai-toggle-settings').on('click', function(e) {
                e.preventDefault();
                $('#wpr-ai-settings').slideToggle(200);
            });

            // Builder
            $('#wpr-ai-generate-btn').on('click', this.generatePage.bind(this));

            // Style selection
            $('.wpr-ai-style-option').on('click', function() {
                $('.wpr-ai-style-option').removeClass('active');
                $(this).addClass('active');
                WprAiBuilder.selectedStyle = $(this).data('style');
            });

            // Result actions
            $('.wpr-ai-new-btn, .wpr-ai-retry-btn').on('click', this.resetForm.bind(this));
        },

        toggleModelOptions: function() {
            var provider = $('#wpr-ai-provider').val();
            var $model = $('#wpr-ai-model');

            $model.find('optgroup').hide();
            $model.find('.wpr-ai-models-' + provider).show();

            // Select first visible option
            var $firstVisible = $model.find('.wpr-ai-models-' + provider + ' option:first');
            if ($firstVisible.length) {
                $model.val($firstVisible.val());
            }
        },

        saveSettings: function() {
            var $status = $('#wpr-ai-settings-status');
            $status.text('Saving...').removeClass('success error');

            $.post(wprAiBuilder.ajaxUrl, {
                action: 'wpr_ai_save_settings',
                nonce: wprAiBuilder.nonce,
                provider: $('#wpr-ai-provider').val(),
                model: $('#wpr-ai-model').val(),
                api_key: $('#wpr-ai-api-key').val()
            }, function(response) {
                if (response.success) {
                    $status.text('Saved!').addClass('success');
                    // Enable generate button
                    $('#wpr-ai-generate-btn').prop('disabled', false);
                    $('.wpr-ai-notice').hide();
                } else {
                    $status.text(response.data || 'Error saving.').addClass('error');
                }
            }).fail(function() {
                $status.text('Network error.').addClass('error');
            });
        },

        testConnection: function() {
            var $status = $('#wpr-ai-settings-status');
            $status.text('Testing...').removeClass('success error');

            // Save first, then test
            this.saveSettings();

            setTimeout(function() {
                $.post(wprAiBuilder.ajaxUrl, {
                    action: 'wpr_ai_test_connection',
                    nonce: wprAiBuilder.nonce
                }, function(response) {
                    if (response.success) {
                        $status.text('Connection successful!').addClass('success');
                    } else {
                        $status.text(response.data || 'Connection failed.').addClass('error');
                    }
                }).fail(function() {
                    $status.text('Network error.').addClass('error');
                });
            }, 1000);
        },

        generatePage: function() {
            var prompt = $('#wpr-ai-prompt').val().trim();
            if (!prompt) {
                alert('Please describe the page you want to create.');
                $('#wpr-ai-prompt').focus();
                return;
            }

            // Show progress
            this.showPanel('progress');
            this.updateProgress('analyze', 10);

            var self = this;

            // Simulate progress steps while waiting for AJAX
            setTimeout(function() { self.updateProgress('ai', 30); }, 500);

            $.post(wprAiBuilder.ajaxUrl, {
                action: 'wpr_ai_generate_page',
                nonce: wprAiBuilder.nonce,
                prompt: prompt,
                page_type: $('#wpr-ai-page-type').val(),
                business_name: $('#wpr-ai-business-name').val(),
                style: this.selectedStyle,
                color_preference: $('#wpr-ai-color').val()
            }, function(response) {
                if (response.success) {
                    self.updateProgress('build', 70);

                    setTimeout(function() {
                        self.updateProgress('create', 100);

                        setTimeout(function() {
                            self.showResult(response.data);
                        }, 500);
                    }, 300);
                } else {
                    self.showError(response.data || 'Unknown error occurred.');
                }
            }).fail(function(xhr) {
                var msg = 'Network error. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.data) {
                    msg = xhr.responseJSON.data;
                }
                self.showError(msg);
            });
        },

        updateProgress: function(step, percent) {
            // Mark previous steps as done
            var steps = ['analyze', 'ai', 'build', 'create'];
            var currentIndex = steps.indexOf(step);

            $('.wpr-ai-progress-step').each(function(i) {
                var $step = $(this);
                if (i < currentIndex) {
                    $step.removeClass('active').addClass('done');
                    $step.find('.wpr-ai-step-icon').html('&#10003;');
                } else if (i === currentIndex) {
                    $step.addClass('active').removeClass('done');
                    $step.find('.wpr-ai-step-icon').html('&#9632;');
                } else {
                    $step.removeClass('active done');
                    $step.find('.wpr-ai-step-icon').html('&#9634;');
                }
            });

            $('.wpr-ai-progress-fill').css('width', percent + '%');
        },

        showResult: function(data) {
            this.showPanel('result');

            $('.wpr-ai-result-title').text(data.title);
            $('.wpr-ai-result-info').text(data.sections_used + ' sections assembled into your page.');
            $('.wpr-ai-edit-btn').attr('href', data.edit_url);
            $('.wpr-ai-view-btn').attr('href', data.view_url);
            if (data.original_url) {
                $('.wpr-ai-original-btn').attr('href', data.original_url).show();
            } else {
                $('.wpr-ai-original-btn').hide();
            }
        },

        showError: function(message) {
            this.showPanel('error');
            $('.wpr-ai-error-message').text(message);
        },

        showPanel: function(panel) {
            $('#wpr-ai-builder-form, #wpr-ai-progress, #wpr-ai-result, #wpr-ai-error').hide();

            switch(panel) {
                case 'form':
                    $('#wpr-ai-builder-form').show();
                    break;
                case 'progress':
                    $('#wpr-ai-progress').show();
                    break;
                case 'result':
                    $('#wpr-ai-result').show();
                    break;
                case 'error':
                    $('#wpr-ai-error').show();
                    break;
            }
        },

        resetForm: function() {
            this.showPanel('form');

            // Reset progress
            $('.wpr-ai-progress-step').removeClass('active done');
            $('.wpr-ai-step-icon').html('&#9634;');
            $('.wpr-ai-progress-fill').css('width', '0%');
        }
    };

    $(document).ready(function() {
        WprAiBuilder.init();
    });

})(jQuery);
