<?php
/** @var string $channelType */
/** @var string $approval */
/** @var string $brandkey */
/** @var string $is_use */
/** @var string $approvalData */
?>

<h1><?php echo esc_html__('outaigate', 'outaigate-admin'); ?></h1>
<form method="POST" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
    <input type="hidden" name="action" value="outaigate_admin_save">
    <?php wp_nonce_field('outaigate_admin_save', 'outaigate_admin'); ?>
    <input type="hidden" name="redirectToUrl" value="<?php echo outaigate_admin_view_pagename(''); ?>">
    <div class="row g-5">
        <div class="col-md-12">
            <fieldset class="mt-3">
                <div class="mb-3 row">
                    <div class="col-md-4">
                        <label for="cookie_content_language"
                               class="form-label"><?php echo esc_html__('ブランドキー入力', 'outaigate-admin') ?></label>
                    </div>
                    <div class="col-md-8 row">
                        <input type="text" id="brandkey" name="outaigate-admin-data[brandkey]" class="form-control" value="<?php echo esc_html($brandkey); ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-4">
                        <label for="cookie_content_language"
                               class="form-label"><?php echo esc_html__('チャンネルタイプ', 'outaigate-admin') ?></label>
                    </div>
                    <div class="col-md-8 row">
                        <div class="col-auto">
                            <input type="radio" id="channelType1" name="outaigate-admin-data[channelType]" class="form-control" value="scenario" <?php echo $channelType=='scenario'?esc_html('checked'):''; ?>>
                            <label for="channelType1"
                                class="form-label"><?php echo esc_html__('チャットボット', 'outaigate-admin') ?></label>
                            <input type="radio" id="channelType2" name="outaigate-admin-data[channelType]" class="form-control" value="chat" <?php echo $channelType=='chat'?esc_html('checked'):''; ?>>
                            <label for="channelType2"
                                class="form-label"><?php echo esc_html__('ウェブチャット', 'outaigate-admin') ?></label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                <div class="col-md-4">
                        <label for="cookie_content_language"
                               class="form-label"><?php echo esc_html__('ホームページに表示', 'outaigate-admin') ?></label>
                    </div>
                    <div class="col-md-8">
                    <input type="hidden" name="outaigate-admin-data[is_use]" value="">
                    <input class="form-check-input" type="checkbox" value="on" id="is_use" name="outaigate-admin-data[is_use]" <?php echo esc_html($is_use); ?>>
                    </div>
                </div>
            </fieldset>

        </div>

    </div>
    <!-- / row -->

    <?php outaigate_admin_submit(esc_html__('Submit')); ?>

</form>