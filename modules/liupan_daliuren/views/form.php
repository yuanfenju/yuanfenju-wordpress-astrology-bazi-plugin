<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('大六壬神机排盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('袖传一课定乾坤，支持天地盘、四课三传精准推演。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <!-- 基础信息 -->
        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 120px; margin-bottom: 0;">
                <label><?php echo $this->t('命主姓名：'); ?></label>
                <input type="text" name="name" required placeholder="<?php echo $this->t('请输入姓名'); ?>" value="<?php echo $this->t('求测者'); ?>">
            </div>
            <div class="form-group" style="flex: 1; min-width: 120px; margin-bottom: 0;">
                <label><?php echo $this->t('本命年(出生年)：'); ?></label>
                <select name="born_year">
                    <?php
                    $current_year = date('Y');
                    for($y=1930; $y<=($current_year); $y++):
                        ?>
                        <option value="<?php echo $y; ?>" <?php selected($y, 1988); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
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

        <!-- 问测时间 -->
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

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('问测日期：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="year" id="yfj_year" style="flex: 1.2;">
                    <?php
                    for($y=1930; $y<=($current_year + 5); $y++):
                        ?>
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

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('问测时辰：'); ?></label>
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

        <!-- 真太阳时校准模块 -->
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

        <!-- 大六壬高级起局参数 -->
        <div style="margin-bottom: 25px; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
            <div style="background: #f1f5f9; padding: 10px 15px; font-size: 14px; font-weight: bold; color: #475569; border-bottom: 1px solid #cbd5e1; display: flex; justify-content: space-between; cursor: pointer;" onclick="jQuery('#yfj-dlr-adv').slideToggle();">
                <span><span class="dashicons dashicons-admin-generic"></span> <?php echo $this->t('大六壬起局设置'); ?></span>
                <span style="font-size: 12px; font-weight: normal;"><?php echo $this->t('点击展开/收起'); ?></span>
            </div>
            <div id="yfj-dlr-adv" style="padding: 15px; background: #fafafa; display: none;">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('贵人方式：'); ?></label>
                    <select name="gui_model">
                        <option value="1" selected><?php echo $this->t('甲戊庚牛羊 (默认)'); ?></option>
                        <option value="0"><?php echo $this->t('甲羊戊庚牛'); ?></option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('贵人行运：'); ?></label>
                    <select name="gui_xing_model">
                        <option value="2" selected><?php echo $this->t('自动计算 (默认)'); ?></option>
                        <option value="1"><?php echo $this->t('昼贵人'); ?></option>
                        <option value="0"><?php echo $this->t('夜贵人'); ?></option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('三传配干：'); ?></label>
                    <select name="pei_model">
                        <option value="0" selected><?php echo $this->t('日旬遁干 (默认)'); ?></option>
                        <option value="1"><?php echo $this->t('时旬遁干'); ?></option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label><?php echo $this->t('是否排行年：'); ?></label>
                    <select name="hang_year">
                        <option value="0" selected><?php echo $this->t('否 (默认)'); ?></option>
                        <option value="1"><?php echo $this->t('是'); ?></option>
                    </select>
                </div>

            </div>
        </div>

        <button type="submit"><?php echo $this->t('排演大六壬'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('六壬神机推演中...'); ?></div>
    <div class="yfj-result-area"></div>
</div>

<script>
    (function() {
        // 真太阳时城市联动
        window.yfjToggleZhen = function(val) {
            document.getElementById('yfj-city-area').style.display = (val === '3') ? 'block' : 'none';
        };
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

        fetch(jsonUrl).then(response => response.json()).then(data => {
            data.forEach(item => {
            let p = item.province;
        let c = item.city;
        let a = item.area === "" ? "<?php echo $this->t('市本级/全区'); ?>" : item.area;
        if (!hierarchicalData[p]) { hierarchicalData[p] = { lat: item.lat, lng: item.lng, cities: {} }; }
        if (!hierarchicalData[p].cities[c]) { hierarchicalData[p].cities[c] = { lat: item.lat, lng: item.lng, areas: [] }; }
        if (a) { hierarchicalData[p].cities[c].areas.push({ area: a, lat: item.lat, lng: item.lng }); }
    });
        provSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
        for (const prov in hierarchicalData) { provSelect.add(new Option(prov, prov)); }
    }).catch(err => {
            console.error('City data failed:', err);
        provSelect.innerHTML = '<option value=""><?php echo $this->t("城市数据加载失败"); ?></option>';
    });

        provSelect.addEventListener('change', function() {
            citySelect.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
            distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
            if (!this.value || !hierarchicalData[this.value]) return;
            updateCoordinates(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);
            for (const city in hierarchicalData[this.value].cities) { citySelect.add(new Option(city, city)); }
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
    })();
</script>