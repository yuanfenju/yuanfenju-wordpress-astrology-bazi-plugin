<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('八字排盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('输入命主出生信息，系统将自动进行精准的运算。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

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
            <label><?php echo $this->t('出生日期：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="year" id="yfj_year" style="flex: 1.2;">
                    <?php
                    $current_year = date('Y');
                    for($y=1930; $y<=($current_year + 5); $y++):
                        ?>
                        <option value="<?php echo $y; ?>" <?php selected($y, 1990); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="month" style="flex: 1;">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?php echo $m; ?>"><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="day" style="flex: 1;">
                    <?php for($d=1; $d<=31; $d++): ?>
                        <option value="<?php echo $d; ?>"><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('出生时辰：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="hours" style="flex: 1;">
                    <?php
                    $zhi_arr = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];
                    for($h=0; $h<24; $h++):
                        $zhi_index = floor((($h + 1) % 24) / 2);
                        $zhi = $zhi_arr[$zhi_index];
                        ?>
                        <option value="<?php echo $h; ?>" <?php selected($h, 12); ?>>
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

        <div class="form-group" style="margin-bottom: 25px;">
            <label><?php echo $this->t('排盘流派：'); ?></label>
            <select name="sect">
                <option value="1" selected><?php echo $this->t('晚子时日柱算明天 (推荐)'); ?></option>
                <option value="2"><?php echo $this->t('晚子时日柱算当天'); ?></option>
            </select>
        </div>

        <button type="submit"><?php echo $this->t('立即排盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在计算中...'); ?></div>
    <div class="yfj-result-area"></div>
</div>

<script>
    (function() {
        // 1. 切换城市区域显示
        window.yfjToggleZhen = function(val) {
            document.getElementById('yfj-city-area').style.display = (val === '3') ? 'block' : 'none';
        };

        // 2. 【新增核心逻辑】更新隐藏的经纬度表单
        function updateCoordinates(lat, lng) {
            if (lat && lng) {
                document.getElementById('raw_latitude').value = lat;
                document.getElementById('raw_longitude').value = lng;
            }
        }

        // 3. 动态加载 city.json 逻辑 (支持三级：省->市->区，并保留坐标)
        const provSelect = document.getElementById('yfj_province');
        const citySelect = document.getElementById('yfj_city');
        const distSelect = document.getElementById('yfj_district');

        let hierarchicalData = {};

        <?php
        // 提取当前后台配置的语言
        $current_lang = get_option('yfj_language', 'zh-cn');
        // 如果是繁体，就加上 _tw 后缀
        $city_file_name = ($current_lang === 'zh-tw') ? 'city_tw.json' : 'city.json';
        ?>

        // 动态加载对应的静态字典
        const jsonUrl = '<?php echo YFJ_PLUGIN_URL; ?>assets/js/<?php echo $city_file_name; ?>';

        fetch(jsonUrl)
            .then(response => response.json())
    .then(data => {
            // 【核心修正】：必须把 lat 和 lng 存进数据结构里
            data.forEach(item => {
            let p = item.province;
        let c = item.city;
        let a = item.area === "" ? "<?php echo $this->t('市本级/全区'); ?>" : item.area;
        // 初始化省份节点，并保留遇到的第一个坐标作为该省兜底
        if (!hierarchicalData[p]) {
            hierarchicalData[p] = { lat: item.lat, lng: item.lng, cities: {} };
        }
        // 初始化城市节点，并保留该市兜底坐标
        if (!hierarchicalData[p].cities[c]) {
            hierarchicalData[p].cities[c] = { lat: item.lat, lng: item.lng, areas: [] };
        }
        // 推入区县精确坐标
        if (a) {
            hierarchicalData[p].cities[c].areas.push({ area: a, lat: item.lat, lng: item.lng });
        }
    });

        // 初始化省份下拉框
        provSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
        for (const prov in hierarchicalData) {
            provSelect.add(new Option(prov, prov));
        }
    })
    .catch(err => {
            console.error('City data failed:', err);
        provSelect.innerHTML = '<option value=""><?php echo $this->t("城市数据加载失败"); ?></option>';
    });

        // 4. 省份变动 -> 联动城市 + 【更新兜底坐标】
        provSelect.addEventListener('change', function() {
            citySelect.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
            distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';

            if (!this.value || !hierarchicalData[this.value]) return;

            // 只要选了省，立刻把该省的经纬度填入隐藏表单
            updateCoordinates(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);

            for (const city in hierarchicalData[this.value].cities) {
                citySelect.add(new Option(city, city));
            }
        });

        // 5. 城市变动 -> 联动区县 + 【更新城市级坐标】
        citySelect.addEventListener('change', function() {
            distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';

            const selectedProv = provSelect.value;
            const selectedCity = this.value;

            if (!selectedProv || !selectedCity || !hierarchicalData[selectedProv].cities[selectedCity]) return;

            const cityData = hierarchicalData[selectedProv].cities[selectedCity];

            // 只要选了市，立刻把该市的经纬度填入隐藏表单
            updateCoordinates(cityData.lat, cityData.lng);

            cityData.areas.forEach(item => {
                const opt = new Option(item.area, item.area);
            // 把精确的区县经纬度绑定在 option 元素上
            opt.dataset.lat = item.lat;
            opt.dataset.lng = item.lng;
            distSelect.add(opt);
        });
        });

        // 6. 区县变动 -> 【更新最终最精确的坐标】
        distSelect.addEventListener('change', function() {
            if (!this.value) return;
            // 选了区，提取挂载在 option 上的 dataset 经纬度
            const selectedOpt = this.options[this.selectedIndex];
            updateCoordinates(selectedOpt.dataset.lat, selectedOpt.dataset.lng);
        });
    })();
</script>