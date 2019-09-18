<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $image_count = 0;
if (!empty($product_images)) {
    $image_count = count($product_images);
} ?>

<?php if ($image_count == 0):

    if (!empty($video)): ?>
        <div class="product-video-preview">
            <video id="player" playsinline controls>
                <source src="<?php echo get_product_video_url($video); ?>" type="video/mp4">
            </video>
        </div>
    <?php endif;

    if (!empty($audio)): ?>
        <div class="product-audio-preview">
            <div id="waveform"></div>
            <div class="waveform-controls">
                <button class="btn btn-secondary btn-backward color-white" onclick="wavesurfer.skipBackward()"><i class="icon-backward m-r-5"></i><?php echo trans("backward"); ?></button>
                <button class="btn btn-secondary btn-play-pause color-white" onclick="wavesurfer.playPause()"><i class="icon-play m-r-5"></i><?php echo trans("play"); ?>&nbsp;/&nbsp;<i class="icon-pause m-r-5"></i><?php echo trans("pause"); ?></button>
                <button class="btn btn-secondary btn-forward color-white" onclick="wavesurfer.skipForward()"><i class="icon-forward m-r-5"></i><?php echo trans("forward"); ?></button>
            </div>
        </div>
    <?php endif; ?>

<?php elseif ($image_count == 1): ?>

    <?php if (!empty($video)): ?>
        <div class="product-video-preview">
            <video id="player" playsinline controls>
                <source src="<?php echo get_product_video_url($video); ?>" type="video/mp4">
            </video>
        </div>

        <?php if (!empty($audio)): ?>
            <div class="product-audio-preview">
                <div id="waveform"></div>
                <div class="waveform-controls">
                    <button class="btn btn-secondary btn-backward color-white" onclick="wavesurfer.skipBackward()"><i class="icon-backward m-r-5"></i><?php echo trans("backward"); ?></button>
                    <button class="btn btn-secondary btn-play-pause color-white" onclick="wavesurfer.playPause()"><i class="icon-play m-r-5"></i><?php echo trans("play"); ?>&nbsp;/&nbsp;<i class="icon-pause m-r-5"></i><?php echo trans("pause"); ?></button>
                    <button class="btn btn-secondary btn-forward color-white" onclick="wavesurfer.skipForward()"><i class="icon-forward m-r-5"></i><?php echo trans("forward"); ?></button>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php if (!empty($audio)): ?>
            <div class="row-custom m-b-30">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4">
                        <?php foreach ($product_images as $image): ?>
                            <div class="product-image-preview product-audio-preview-cover">
                                <a class="image-popup lightbox" href="<?php echo get_product_image_url($image, 'image_big'); ?>" data-effect="mfp-zoom-out" title="">
                                    <img src="<?php echo get_product_image_url($image, 'image_default'); ?>" alt="<?php echo html_escape($product->title); ?>" class="img-fluid">
                                </a>
                            </div>
                            <?php break;
                        endforeach; ?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-8">
                        <div class="product-audio-preview product-audio-preview-with-cover">
                            <div id="waveform"></div>
                            <div class="waveform-controls">
                                <button class="btn btn-secondary btn-backward color-white" onclick="wavesurfer.skipBackward()"><i class="icon-backward m-r-5"></i><?php echo trans("backward"); ?></button>
                                <button class="btn btn-secondary btn-play-pause color-white" onclick="wavesurfer.playPause()"><i class="icon-play m-r-5"></i><?php echo trans("play"); ?>&nbsp;/&nbsp;<i class="icon-pause m-r-5"></i><?php echo trans("pause"); ?></button>
                                <button class="btn btn-secondary btn-forward color-white" onclick="wavesurfer.skipForward()"><i class="icon-forward m-r-5"></i><?php echo trans("forward"); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($product_images as $image): ?>
                <div class="product-image-preview">
                    <a class="image-popup lightbox" href="<?php echo get_product_image_url($image, 'image_big'); ?>" data-effect="mfp-zoom-out" title="">
                        <img src="<?php echo get_product_image_url($image, 'image_default'); ?>" alt="<?php echo html_escape($product->title); ?>" class="img-fluid">
                    </a>
                </div>
                <?php break;
            endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

<?php else: ?>

    <div class="product-slider-container">
        <div class="left">
            <div class="dots-container slider-dots slider-custom-scrollbar <?php echo (count($product_images) < 2) ? 'hide-dosts-mobile' : ''; ?>">
                <?php if (!empty($product_images)):
                    foreach ($product_images as $image): ?>
                        <button class="dot dot_slider"><img src="<?php echo get_product_image_url($image, 'image_small'); ?>" alt="dot"></button>
                    <?php endforeach;
                endif; ?>
                <?php if (!empty($video)): ?>
                    <button class="dot dot_slider">
                        <img src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="dot">
                        <img src="<?php echo base_url().'assets/img/play-circle.svg'; ?>" class="dot-video-icon" alt="dot">
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="right">
            <div id="product-slider" class="owl-carousel product-slider">
                <?php if (!empty($product_images)):
                    foreach ($product_images as $image): ?>
                        <div class="item">
                            <a class="image-popup lightbox" href="<?php echo get_product_image_url($image, 'image_big'); ?>" data-effect="mfp-zoom-out" title="">
                                <img src="<?php echo get_product_image_url($image, 'image_default'); ?>" alt="<?php echo html_escape($product->title); ?>">
                            </a>
                        </div>
                    <?php endforeach;
                endif; ?>
                <?php if (!empty($video)): ?>
                    <div class="item">
                        <video id="player" playsinline controls>
                            <source src="<?php echo get_product_video_url($video); ?>" type="video/mp4">
                        </video>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($audio)): ?>
        <div class="product-audio-preview">
            <div id="waveform"></div>
            <div class="waveform-controls">
                <button class="btn btn-secondary btn-backward color-white" onclick="wavesurfer.skipBackward()"><i class="icon-backward m-r-5"></i><?php echo trans("backward"); ?></button>
                <button class="btn btn-secondary btn-play-pause color-white" onclick="wavesurfer.playPause()"><i class="icon-play m-r-5"></i><?php echo trans("play"); ?>&nbsp;/&nbsp;<i class="icon-pause m-r-5"></i><?php echo trans("pause"); ?></button>
                <button class="btn btn-secondary btn-forward color-white" onclick="wavesurfer.skipForward()"><i class="icon-forward m-r-5"></i><?php echo trans("forward"); ?></button>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
if (!empty($audio)):
    $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
    if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false)):?>
        <audio id="ie_player" src="<?php echo get_product_audio_url($audio); ?>" style="visibility:hidden;"></audio>
        <script>
            $(document).ready(function () {
                var audio = document.getElementById('ie_player');
                var playing = false;
                $('.btn-play-pause').click(function () {
                    if (playing == false) {
                        audio.play();
                        playing = true;
                    } else {
                        audio.pause();
                        playing = false;
                    }
                });
                $('.btn-forward').click(function () {
                    audio.currentTime += 15;
                });
                $('.btn-backward').click(function () {
                    audio.currentTime -= 15;
                });
            });
        </script>
    <?php else: ?>
        <script src="<?php echo base_url(); ?>assets/js/wavesurfer.min.js"></script>
        <script>
            var ctx = document.createElement('canvas').getContext('2d');
            var linGrad = ctx.createLinearGradient(0, 32, 0, 128);
            linGrad.addColorStop(0.5, 'rgba(180, 180, 180, 1.000)');
            linGrad.addColorStop(0.5, 'rgba(210, 210, 210, 1.000)');

            var wavesurfer = WaveSurfer.create({
                // Use the id or class-name of the element you created, as a selector
                container: '#waveform',
                // The color can be either a simple CSS color or a Canvas gradient
                waveColor: '#bbb',
                progressColor: 'hsla(200, 100%, 30%, 0.5)',
                cursorColor: '#acabab',
                // This parameter makes the waveform look like SoundCloud's player
                barWidth: 2,
                backend: 'MediaElement'
            });
            wavesurfer.on('loading', function (percents) {
            });
            wavesurfer.on('ready', function (percents) {
            });
            wavesurfer.load('<?php echo get_product_audio_url($audio); ?>');
        </script>
    <?php endif;
endif; ?>
