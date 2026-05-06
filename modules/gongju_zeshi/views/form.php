<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('吉日查询'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('选择您计划办理的重要事项及查询的时间范围，系统将为您挑选出最适合的黄道吉日。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <div style="margin-bottom: 25px; padding: 20px; background: #f8fafc; border: 1px solid var(--yfj-border); border-radius: 6px;">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">

                <!-- 时间范围 -->
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #334155;">
                        <?php echo $this->t('查询范围：'); ?>
                    </label>
                    <select name="future" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="0"><?php echo $this->t('未来 7 天'); ?></option>
                        <option value="1" selected><?php echo $this->t('未来半个月'); ?></option>
                        <option value="2"><?php echo $this->t('未来 1 个月'); ?></option>
                        <option value="3"><?php echo $this->t('未来 3 个月'); ?></option>
                    </select>
                </div>

                <!-- 择吉事项 -->
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #334155;">
                        <?php echo $this->t('择吉事项：'); ?>
                    </label>
                    <select name="incident" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="0"><?php echo $this->t('迁徙 / 搬家'); ?></option>
                        <option value="1"><?php echo $this->t('修造 / 装修'); ?></option>
                        <option value="2"><?php echo $this->t('入宅'); ?></option>
                        <option value="3"><?php echo $this->t('纳采 / 订婚 / 结婚'); ?></option>
                        <option value="4"><?php echo $this->t('嫁娶 / 领证'); ?></option>
                        <option value="5"><?php echo $this->t('求嗣 / 剖腹产'); ?></option>
                        <option value="6"><?php echo $this->t('纳财'); ?></option>
                        <option value="7"><?php echo $this->t('开市'); ?></option>
                        <option value="8"><?php echo $this->t('交易'); ?></option>
                        <option value="9"><?php echo $this->t('置产'); ?></option>
                        <option value="10"><?php echo $this->t('动土'); ?></option>
                        <option value="11"><?php echo $this->t('出行'); ?></option>
                        <option value="12"><?php echo $this->t('安葬'); ?></option>
                        <option value="13"><?php echo $this->t('祭祀'); ?></option>
                        <option value="14"><?php echo $this->t('祈福'); ?></option>
                        <option value="15"><?php echo $this->t('沐浴'); ?></option>
                        <option value="16"><?php echo $this->t('订盟'); ?></option>
                        <option value="17"><?php echo $this->t('纳婿'); ?></option>
                        <option value="18"><?php echo $this->t('修坟'); ?></option>
                        <option value="19"><?php echo $this->t('破土'); ?></option>
                        <option value="21"><?php echo $this->t('立碑'); ?></option>
                        <option value="22"><?php echo $this->t('开生坟'); ?></option>
                        <option value="23"><?php echo $this->t('合寿木'); ?></option>
                        <option value="24"><?php echo $this->t('入殓'); ?></option>
                        <option value="25"><?php echo $this->t('移柩'); ?></option>
                        <option value="26"><?php echo $this->t('伐木'); ?></option>
                        <option value="27"><?php echo $this->t('掘井'); ?></option>
                        <option value="28"><?php echo $this->t('挂匾'); ?></option>
                        <option value="29"><?php echo $this->t('栽种'); ?></option>
                        <option value="30"><?php echo $this->t('入学'); ?></option>
                        <option value="31"><?php echo $this->t('理发'); ?></option>
                        <option value="32"><?php echo $this->t('会亲友'); ?></option>
                        <option value="33"><?php echo $this->t('赴任'); ?></option>
                        <option value="34"><?php echo $this->t('求医'); ?></option>
                        <option value="35"><?php echo $this->t('治病'); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" style="width: 100%;"><?php echo $this->t('立即查询'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none; text-align: center; margin-top: 15px;"><?php echo $this->t('正在推演吉日中...'); ?></div>
    <div class="yfj-result-area"></div>
</div>