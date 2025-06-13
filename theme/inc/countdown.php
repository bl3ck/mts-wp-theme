<?php
function render_application_badge_shortcode() {
    ob_start();

    $home_query = new WP_Query([
        'post_type'      => 'page',
        'posts_per_page' => 1,
        'title'         => 'Home',
        'post_status'   => 'publish',
    ]);

    $application_start = null;
    $application_deadline = null;
    $apply_link = null;
    $show_count_down = false;

    if ($home_query->have_posts()) {
        while ($home_query->have_posts()) {
            $home_query->the_post();
            $show_count_down = get_field('show_count_down', get_the_ID(), false);
            
            // Only proceed if countdown should be shown
            if (!$show_count_down) {
                wp_reset_postdata();
                return '';
            }
            
            $application_start = get_field('application_starts', get_the_ID(), false);
            $application_deadline = get_field('application_deadline', get_the_ID(), false);
            $apply_link = get_field('application_link', get_the_ID(), false);
        }
        wp_reset_postdata();
    }

    try {
        // Get WordPress timezone
        $timezone_string = get_option('timezone_string');
        $gmt_offset = get_option('gmt_offset');
        
        if (empty($timezone_string) && $gmt_offset) {
            $timezone_string = timezone_name_from_abbr('', $gmt_offset * 3600, false);
        }
        
        $timezone_string = $timezone_string ?: 'UTC';
        $timezone = new DateTimeZone($timezone_string);
        $now = new DateTime('now', $timezone);

        // Parse dates with timezone
        $start_date = $application_start ? DateTime::createFromFormat('Y-m-d H:i:s', $application_start, $timezone) : false;
        $deadline = $application_deadline ? DateTime::createFromFormat('Y-m-d H:i:s', $application_deadline, $timezone) : false;
        
        // If time not included, set to start/end of day
        if ($start_date && $application_start && strpos($application_start, ':') === false) {
            $start_date->setTime(0, 0, 0);
        }
        if ($deadline && $application_deadline && strpos($application_deadline, ':') === false) {
            $deadline->setTime(23, 59, 59);
        }

        if (!$start_date || !$deadline) {
            return '<!-- Application dates not set -->';
        }

        // Calculate time differences
        $seconds_until_start = $start_date->getTimestamp() - $now->getTimestamp();
        $seconds_until_deadline = $deadline->getTimestamp() - $now->getTimestamp();

        // Determine application window status
        $is_before_window = $seconds_until_start > 0;
        $is_during_window = $seconds_until_start <= 0 && $seconds_until_deadline > 0;
        $is_after_window = $seconds_until_deadline <= 0;
        $is_last_day = $is_during_window && $seconds_until_deadline <= 86400;
        $less_than_2_days = $is_during_window && $seconds_until_deadline <= 172800; // 48 hours
        $recently_closed = $is_after_window && abs($seconds_until_deadline) <= 43200; // 12 hours grace period

        if ($is_before_window || $is_during_window || $recently_closed) {
            ?>
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
                <div class="inline-flex items-center space-x-2 rounded-full px-4 py-1 text-sm font-medium shadow-sm
                    <?php echo $is_during_window ? 'bg-green-50 text-green-600' : ($is_before_window ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-600'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0
                        <?php echo $is_during_window ? 'text-green-500' : ($is_before_window ? 'text-blue-500' : 'text-gray-500'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="<?php echo $is_during_window ? 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M6 18L18 6M6 6l12 12'; ?>" />
                    </svg>
                    <?php if ($is_before_window): ?>
                        <span>Applications will open in <?php echo human_time_diff($now->getTimestamp(), $start_date->getTimestamp()); ?></span>
                    <?php elseif ($is_last_day): ?>
                        <span>Final day to apply</span>
                        <span class="text-xs text-current">
                            (Closes in <?php echo floor($seconds_until_deadline / 3600); ?>h <?php echo floor(($seconds_until_deadline % 3600) / 60); ?>m)
                        </span>
                    <?php elseif ($less_than_2_days): ?>
                        <span>Applications open - Closing soon</span>
                        <span class="text-xs text-current">
                            (<?php echo floor($seconds_until_deadline / 3600); ?>h <?php echo floor(($seconds_until_deadline % 3600) / 60); ?>m remaining)
                        </span>
                    <?php elseif ($is_during_window): ?>
                        <span>Applications open</span>
                        <span class="text-xs text-current">
                            (Closes in <?php echo floor($seconds_until_deadline / 86400); ?> day<?php echo floor($seconds_until_deadline / 86400) !== 1 ? 's' : ''; ?>)
                        </span>
                    <?php elseif ($recently_closed): ?>
                        <span>Applications closed</span>
                        <span class="text-xs text-current">
                            (Closed <?php echo human_time_diff($deadline->getTimestamp(), $now->getTimestamp()); ?> ago)
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ($is_during_window && $apply_link): ?>
                    <a href="<?php echo esc_url($apply_link); ?>"
                        class="inline-flex items-center rounded-full bg-green-700 text-white text-sm px-8 py-1.5 hover:bg-green-900 transition duration-300">
                        Apply Now
                    </a>
                <?php endif; ?>
            </div>
            <?php
        }
    } catch (Exception $e) {
        return '<!-- Error processing application dates: ' . esc_html($e->getMessage()) . ' -->';
    }

    return ob_get_clean();
}
add_shortcode('application_badge', 'render_application_badge_shortcode');