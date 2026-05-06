<style>
    /* 优化的数字输入框样式 */
    .yfj-num-input {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        color: #334155;
        outline: none;
        transition: all 0.3s ease;
        box-sizing: border-box;
        background: #fff;
    }
    .yfj-num-input:focus {
        border-color: #b45309;
        box-shadow: 0 0 0 3px rgba(180, 83, 9, 0.1);
    }
</style>

<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('六爻纳甲排盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('天机尽泄于六爻之中，支持纳甲起卦、手工摇卦等多种方式。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 120px; margin-bottom: 0;">
                <label><?php echo $this->t('公历出生年：'); ?></label>
                <select name="born_year">
                    <?php
                    $current_year = date('Y');
                    for($y=1930; $y<=($current_year); $y++):
                        ?>
                        <option value="<?php echo $y; ?>" <?php selected($y, 1990); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('性别：'); ?></label>
                <div style="padding-top: 10px;">
                    <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="sex" value="0" checked> <?php echo $this->t('男性'); ?>
                    </label>
                    <label style="display:inline; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="sex" value="1"> <?php echo $this->t('女性'); ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px; background: #f8fafc; padding: 15px; border: 1px solid #cbd5e1; border-radius: 6px;">
            <label style="margin-bottom: 10px; color: #0f172a; font-size: 15px;"><span class="dashicons dashicons-randomize"></span> <?php echo $this->t('选择起卦方式：'); ?></label>
            <select name="pan_model" id="yfj_pan_model" onchange="yfjLiuyaoToggle(this.value)" style="font-weight: bold; color: #b45309; border-color: #cbd5e1;">
                <option value="1" selected><?php echo $this->t('自动起卦 (推荐)'); ?></option>
                <option value="2"><?php echo $this->t('自选时间起卦'); ?></option>
                <option value="3"><?php echo $this->t('终身卦'); ?></option>
                <option value="4"><?php echo $this->t('手工指定六爻'); ?></option>
                <option value="5"><?php echo $this->t('指定整数数字起卦'); ?></option>
                <option value="6"><?php echo $this->t('单数起卦'); ?></option>
                <option value="7"><?php echo $this->t('双数起卦 (上/下卦数)'); ?></option>
            </select>

            <div id="yfj-ly-manual" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #cbd5e1;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div><label><?php echo $this->t('第六爻(上)：'); ?></label><select name="gua_yao6"><option value="0"><?php echo $this->t('少阴'); ?> - -</option><option value="1"><?php echo $this->t('少阳'); ?> —</option><option value="2"><?php echo $this->t('老阴'); ?> X</option><option value="3"><?php echo $this->t('老阳'); ?> O</option></select></div>
                    <div><label><?php echo $this->t('第五爻：'); ?></label><select name="gua_yao5"><option value="0"><?php echo $this->t('少阴'); ?> - -</option><option value="1"><?php echo $this->t('少阳'); ?> —</option><option value="2"><?php echo $this->t('老阴'); ?> X</option><option value="3"><?php echo $this->t('老阳'); ?> O</option></select></div>
                    <div><label><?php echo $this->t('第四爻：'); ?></label><select name="gua_yao4"><option value="0"><?php echo $this->t('少阴'); ?> - -</option><option value="1"><?php echo $this->t('少阳'); ?> —</option><option value="2"><?php echo $this->t('老阴'); ?> X</option><option value="3"><?php echo $this->t('老阳'); ?> O</option></select></div>
                    <div><label><?php echo $this->t('第三爻：'); ?></label><select name="gua_yao3"><option value="0"><?php echo $this->t('少阴'); ?> - -</option><option value="1"><?php echo $this->t('少阳'); ?> —</option><option value="2"><?php echo $this->t('老阴'); ?> X</option><option value="3"><?php echo $this->t('老阳'); ?> O</option></select></div>
                    <div><label><?php echo $this->t('第二爻：'); ?></label><select name="gua_yao2"><option value="0"><?php echo $this->t('少阴'); ?> - -</option><option value="1"><?php echo $this->t('少阳'); ?> —</option><option value="2"><?php echo $this->t('老阴'); ?> X</option><option value="3"><?php echo $this->t('老阳'); ?> O</option></select></div>
                    <div><label><?php echo $this->t('第一爻(初)：'); ?></label><select name="gua_yao1"><option value="0"><?php echo $this->t('少阴'); ?> - -</option><option value="1"><?php echo $this->t('少阳'); ?> —</option><option value="2"><?php echo $this->t('老阴'); ?> X</option><option value="3"><?php echo $this->t('老阳'); ?> O</option></select></div>
                </div>
            </div>

            <div id="yfj-ly-single-num" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #cbd5e1;">
                <label style="display:block; margin-bottom:8px;"><?php echo $this->t('输入数字：'); ?></label>
                <input type="number" class="yfj-num-input" name="number" placeholder="<?php echo $this->t('请输入起卦数字 (如: 88)'); ?>" step="1">
            </div>

            <div id="yfj-ly-double-num" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #cbd5e1;">
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label style="display:block; margin-bottom:8px;"><?php echo $this->t('上卦数字：'); ?></label>
                        <input type="number" class="yfj-num-input" name="number_up" placeholder="<?php echo $this->t('上数'); ?>" step="1">
                    </div>
                    <div style="flex: 1;">
                        <label style="display:block; margin-bottom:8px;"><?php echo $this->t('下卦数字：'); ?></label>
                        <input type="number" class="yfj-num-input" name="number_down" placeholder="<?php echo $this->t('下数'); ?>" step="1">
                    </div>
                </div>
            </div>

            <div id="yfj-ly-add-time" style="display: none; margin-top: 15px;">
                <label style="display:inline; font-weight: normal; cursor: pointer; color: #0f172a;">
                    <input type="checkbox" name="yao_add_time" value="1"> <?php echo $this->t('动爻计算是否加此时辰数'); ?>
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('起盘时间类型：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="type" value="1" checked> <?php echo $this->t('公历 (阳历)'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="type" value="0"> <?php echo $this->t('农历 (阴历)'); ?>
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('起盘日期：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="year" style="flex: 1.2;">
                    <?php for($y=1930; $y<=($current_year + 5); $y++): ?>
                        <option value="<?php echo $y; ?>" <?php selected($y, $current_year); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="month" style="flex: 1;">
                    <?php $current_month = date('n'); for($m=1; $m<=12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php selected($m, $current_month); ?>><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="day" style="flex: 1;">
                    <?php $current_day = date('j'); for($d=1; $d<=31; $d++): ?>
                        <option value="<?php echo $d; ?>" <?php selected($d, $current_day); ?>><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label><?php echo $this->t('起盘时辰：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="hours" style="flex: 1;">
                    <?php
                    $current_hour = date('G');
                    $zhi_arr = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];
                    for($h=0; $h<24; $h++):
                        $zhi_index = floor((($h + 1) % 24) / 2);
                        $zhi = $zhi_arr[$zhi_index];
                        ?>
                        <option value="<?php echo $h; ?>" <?php selected($h, $current_hour); ?>>
                            <?php echo sprintf("%02d", $h); ?>:00 - <?php echo $this->t($zhi . '时'); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select name="minute" style="flex: 1;">
                    <option value="0"><?php echo $this->t('未知分'); ?></option>
                    <?php for($min=0; $min<60; $min++): ?>
                        <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <button type="submit"><?php echo $this->t('立即排盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在推演六爻神机...'); ?></div>
    <div class="yfj-result-area"></div>
</div>

<script>
    function yfjLiuyaoToggle(val) {
        document.getElementById('yfj-ly-manual').style.display = 'none';
        document.getElementById('yfj-ly-single-num').style.display = 'none';
        document.getElementById('yfj-ly-double-num').style.display = 'none';
        document.getElementById('yfj-ly-add-time').style.display = 'none';

        if (val === '4') {
            document.getElementById('yfj-ly-manual').style.display = 'block';
        } else if (val === '5' || val === '6') {
            document.getElementById('yfj-ly-single-num').style.display = 'block';
            document.getElementById('yfj-ly-add-time').style.display = 'block';
        } else if (val === '7') {
            document.getElementById('yfj-ly-double-num').style.display = 'block';
            document.getElementById('yfj-ly-add-time').style.display = 'block';
        }
    }
</script>