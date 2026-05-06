<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('大六壬金口诀起课'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('输入命主信息与起课条件，系统将自动进行高精度金口诀排盘。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <input type="hidden" name="action" value="yfj_jinkoujue_submit">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <!-- 姓名与性别 -->
        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('命主姓名：'); ?></label>
                <input type="text" name="name" required placeholder="<?php echo $this->t('请输入姓名'); ?>"
                       value="<?php echo $this->t('求测者'); ?>">
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

        <!-- 日期类型 -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('日期类型：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="type" value="1" checked> <?php echo $this->t('公历 (阳历)'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="type" value="0"> <?php echo $this->t('农历 (阴历)'); ?>
                </label>
            </div>
        </div>

        <!-- 起盘日期 -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('起课日期：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="year" id="yfj_year" style="flex: 1.2;">
                    <?php
                    $current_year = date('Y');
                    for($y=1930; $y<=($current_year + 5); $y++):
                        ?>
                        <option value="<?php echo $y; ?>" <?php selected($y, $current_year); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="month" style="flex: 1;">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php selected($m, date('n')); ?>><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="day" style="flex: 1;">
                    <?php for($d=1; $d<=31; $d++): ?>
                        <option value="<?php echo $d; ?>" <?php selected($d, date('j')); ?>><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <!-- 起盘时辰 -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('起课时辰：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="hours" style="flex: 1;">
                    <option value="0"><?php echo $this->t('未知时'); ?></option>
                    <?php
                    $zhi_arr = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];
                    for($h=0; $h<24; $h++):
                        $zhi_index = floor((($h + 1) % 24) / 2);
                        $zhi = $zhi_arr[$zhi_index];
                        ?>
                        <option value="<?php echo $h; ?>" <?php selected($h, date('G')); ?>>
                            <?php echo sprintf("%02d", $h); ?>:00 - <?php echo $this->t($zhi . '时'); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select name="minute" style="flex: 1;">
                    <option value="0"><?php echo $this->t('未知分'); ?></option>
                    <?php for($min=0; $min<60; $min++): ?>
                        <option value="<?php echo $min; ?>" <?php selected($min, intval(date('i'))); ?>><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <!-- 【恢复】真太阳时校准 -->
        <div class="form-group" style="margin-bottom: 20px; background: #f8fafc; padding: 15px; border: 1px solid var(--yfj-border); border-radius: 6px;">
            <label style="margin-bottom: 10px;"><?php echo $this->t('真太阳时校准：'); ?></label>
            <div style="padding-top: 5px; margin-bottom: 10px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="zhen" value="2" checked onchange="yfjToggleZhen(this.value)"> <?php echo $this->t('不使用'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="zhen" value="3" onchange="yfjToggleZhen(this.value)"> <?php echo $this->t('使用真太阳时'); ?>
                </label>
            </div>

            <!-- 隐藏的经纬度字段 -->
            <input type="hidden" name="longitude" id="raw_longitude" value="116.405285">
            <input type="hidden" name="latitude" id="raw_latitude" value="39.904989">

            <div id="yfj-city-area" style="display: none; padding-top: 10px; border-top: 1px dashed #cbd5e1; margin-top: 10px;">
                <div style="display: flex; gap: 10px;">
                    <select name="province" id="yfj_province" style="flex: 1;">
                        <option value=""><?php echo $this->t('加载中...'); ?></option>
                    </select>
                    <select name="city" id="yfj_city" style="flex: 1;">
                        <option value=""><?php echo $this->t('请选择市'); ?></option>
                    </select>
                    <select name="district" id="yfj_district" style="flex: 1;">
                        <option value=""><?php echo $this->t('请选择区'); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 本命年 与 是否排行年 -->
        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('本命年(出生年)：'); ?></label>
                <select name="born_year" style="width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; border: 1px solid var(--yfj-border);">
                    <?php
                    $born_default = 1990;
                    for($i = 1910; $i <= 2100; $i++) {
                        echo '<option value="'.$i.'"'.($i==$born_default ? ' selected' : '').'>'.$i.' '.$this->t('年').'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('是否排行年：'); ?></label>
                <div style="padding-top: 10px;">
                    <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="hang_year" value="0" checked> <?php echo $this->t('否 (默认)'); ?>
                    </label>
                    <label style="display:inline; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="hang_year" value="1"> <?php echo $this->t('是'); ?>
                    </label>
                </div>
            </div>
        </div>

        <!-- 换将 与 贵人方式 -->
        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('换将方式：'); ?></label>
                <div style="padding-top: 10px;">
                    <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="jiang_model" value="0" checked> <?php echo $this->t('交节换将 (默认)'); ?>
                    </label>
                    <label style="display:inline; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="jiang_model" value="1"> <?php echo $this->t('中气换将'); ?>
                    </label>
                </div>
            </div>
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('贵人方式：'); ?></label>
                <div style="padding-top: 10px;">
                    <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="gui_model" value="1" checked> <?php echo $this->t('甲戊庚牛羊 (默认)'); ?>
                    </label>
                    <label style="display:inline; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="gui_model" value="0"> <?php echo $this->t('甲羊戊庚牛'); ?>
                    </label>
                </div>
            </div>
        </div>

        <!-- 贵人行运 -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('贵人行运：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="gui_xing_model" value="2" checked> <?php echo $this->t('自动卯酉分 (默认)'); ?>
                </label>
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="gui_xing_model" value="0"> <?php echo $this->t('夜'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="gui_xing_model" value="1"> <?php echo $this->t('昼'); ?>
                </label>
            </div>
        </div>

        <!-- 定地分方式 -->
        <div class="form-group" style="margin-bottom: 25px; background: #f8fafc; padding: 15px; border: 1px solid #e2e8f0; border-radius: 6px;">
            <label style="margin-bottom: 10px; display: block;"><?php echo $this->t('定地分方式：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="difen_model" value="1"> <?php echo $this->t('选定'); ?>
                </label>
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="difen_model" value="2"> <?php echo $this->t('报数'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="difen_model" value="3" checked> <?php echo $this->t('随机数 (默认)'); ?>
                </label>
            </div>

            <!-- 动态地分辅助输入区 -->
            <div id="yfj-difen-aux-area" style="display: none; padding-top: 15px; border-top: 1px dashed #cbd5e1; margin-top: 15px;">
                <!-- 选定：十二地支 -->
                <select id="yfj-dropdown-difen" name="difen_model_value" disabled style="display: none; width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; background-color: #fff; color: #334155; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.2s;">
                    <?php foreach(['子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥'] as $zhi): ?>
                        <option value="<?php echo $zhi; ?>"><?php echo $this->t($zhi); ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- 报数：纯数字 -->
                <input type="text" id="yfj-input-difen" name="difen_model_value" disabled style="display: none; width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; background-color: #fff; color: #334155; font-size: 14px; box-sizing: border-box; outline: none; transition: border-color 0.2s;" placeholder="<?php echo $this->t('请输入起课数字（例如：8）'); ?>" onkeyup="this.value=this.value.replace(/\D/g,'')">
            </div>
        </div>

        <button type="submit"><?php echo $this->t('立即起课'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在推演大六壬金口诀...'); ?></div>
    <div class="yfj-result-area"></div>
</div>

<script>
    // 1. 真太阳时显示切换
    window.yfjToggleZhen = function(val) {
        document.getElementById('yfj-city-area').style.display = (val === '3') ? 'block' : 'none';
    };

    (function() {
        // ==========================================
        // 真太阳时：省市区联动与经纬度提取逻辑
        // ==========================================
        function updateCoordinates(lat, lng) {
            if (lat && lng) {
                document.getElementById('raw_latitude').value = lat;
                document.getElementById('raw_longitude').value = lng;
            }
        }

        const provSelect = document.getElementById('yfj_province');
        const citySelect = document.getElementById('yfj_city');
        const distSelect = document.getElementById('yfj_district');
        let hierarchicalData = {};

        <?php
        $current_lang = get_option('yfj_language', 'zh-cn');
        $city_file_name = ($current_lang === 'zh-tw') ? 'city_tw.json' : 'city.json';
        ?>
        const jsonUrl = '<?php echo YFJ_PLUGIN_URL; ?>assets/js/<?php echo $city_file_name; ?>';

        fetch(jsonUrl)
            .then(response => response.json())
    .then(data => {
            data.forEach(item => {
            let p = item.province;
        let c = item.city;
        let a = item.area === "" ? "<?php echo $this->t('市本级/全区'); ?>" : item.area;

        if (!hierarchicalData[p]) {
            hierarchicalData[p] = { lat: item.lat, lng: item.lng, cities: {} };
        }
        if (!hierarchicalData[p].cities[c]) {
            hierarchicalData[p].cities[c] = { lat: item.lat, lng: item.lng, areas: [] };
        }
        if (a) {
            hierarchicalData[p].cities[c].areas.push({ area: a, lat: item.lat, lng: item.lng });
        }
    });

        provSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
        for (const prov in hierarchicalData) {
            provSelect.add(new Option(prov, prov));
        }
    })
    .catch(err => {
            console.error('City data failed:', err);
        provSelect.innerHTML = '<option value=""><?php echo $this->t("城市数据加载失败"); ?></option>';
    });

        provSelect.addEventListener('change', function() {
            citySelect.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
            distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
            if (!this.value || !hierarchicalData[this.value]) return;

            updateCoordinates(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);
            for (const city in hierarchicalData[this.value].cities) {
                citySelect.add(new Option(city, city));
            }
        });

        citySelect.addEventListener('change', function() {
            distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
            const selectedProv = provSelect.value;
            const selectedCity = this.value;
            if (!selectedProv || !selectedCity || !hierarchicalData[selectedProv].cities[selectedCity]) return;

            const cityData = hierarchicalData[selectedProv].cities[selectedCity];
            updateCoordinates(cityData.lat, cityData.lng);

            cityData.areas.forEach(item => {
                const opt = new Option(item.area, item.area);
            opt.dataset.lat = item.lat;
            opt.dataset.lng = item.lng;
            distSelect.add(opt);
        });
        });

        distSelect.addEventListener('change', function() {
            if (!this.value) return;
            const selectedOpt = this.options[this.selectedIndex];
            updateCoordinates(selectedOpt.dataset.lat, selectedOpt.dataset.lng);
        });

        // ==========================================
        // 地分方式联动逻辑（严格处理 disabled）
        // ==========================================
        var difenRadios = document.querySelectorAll('input[name="difen_model"]');
        var auxArea = document.getElementById('yfj-difen-aux-area');
        var dropDifen = document.getElementById('yfj-dropdown-difen');
        var inputDifen = document.getElementById('yfj-input-difen');

        function updateDifenUI() {
            var selectedVal = '3';
            for (var i = 0; i < difenRadios.length; i++) {
                if (difenRadios[i].checked) {
                    selectedVal = difenRadios[i].value;
                    break;
                }
            }

            if (selectedVal === '1') {
                auxArea.style.display = 'block';
                dropDifen.style.display = 'block';
                dropDifen.disabled = false;
                inputDifen.style.display = 'none';
                inputDifen.disabled = true;
            } else if (selectedVal === '2') {
                auxArea.style.display = 'block';
                dropDifen.style.display = 'none';
                dropDifen.disabled = true;
                inputDifen.style.display = 'block';
                inputDifen.disabled = false;
            } else {
                auxArea.style.display = 'none';
                dropDifen.style.display = 'none';
                dropDifen.disabled = true;
                inputDifen.style.display = 'none';
                inputDifen.disabled = true;
            }
        }

        for (var i = 0; i < difenRadios.length; i++) {
            difenRadios[i].addEventListener('change', updateDifenUI);
        }
        updateDifenUI();

    })();
</script>