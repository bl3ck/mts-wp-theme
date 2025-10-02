<?php
/**
 * Template Name: Single Report
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header(); ?>

<?php
$pdf = get_field('pdf_report', get_the_ID())
?>
<div class="page-container">
    <article class="grid sm:grid-cols-12 gap-4 ">
        <?php if ($pdf): ?>
            <div class="col-span-5 rounded shadow">
                <!-- PDF Embed Option -->
                <div class="mb-4">
                    <iframe src="<?php echo esc_url($pdf['url']) ?>#view=fitH" class="w-full h-[400px] border border-gray-300 rounded"></iframe>
                    <p class="px-4 text-sm text-gray-500 mt-1">Note: PDF viewer may not work in all browsers. <a href="<?php echo esc_url($pdf['url']); ?>" class="text-green-600 hover:underline">Download instead</a>.</p>
                </div>
                <div class="p-4">
                    <!-- Download Link -->
                    <a href="<?php echo esc_url($pdf['url']); ?>" target="_blank" class="inline-block bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
                        Download Full Report (PDF)
                    </a>

                    <!-- File Info -->
                    <p class="text-sm text-gray-500 mt-2">
                        File: <?php echo esc_html($pdf['filename']); ?> (<?php echo size_format($pdf['filesize']); ?>)
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-span-7">
            <?php if (has_post_thumbnail()): ?>
                <img src="<?php the_post_thumbnail_url('large') ?>" alt="<?php the_title(); ?>" class="mb-6 h-[400px] w-full object-cover rounded">
            <?php endif; ?>

            <div class="mb-6 text-gray-700">
                <?php the_content() ?>
            </div>
        </div>
    </article>
</div>

<?php get_footer(); ?>