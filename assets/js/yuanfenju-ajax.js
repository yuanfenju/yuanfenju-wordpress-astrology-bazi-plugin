// ==========================================
// 🌟 1. 占星【双模 + 双语 + 百科】多维字典矩阵
// ==========================================
const YFJ_Astro_Dict = {
    signs: {
        'aries': { text_cn: '羊', text_tw: '羊', name_cn: '白羊座', name_tw: '白羊座', en: 'Aries', symbol: '♈', ele_cn: '火象', ele_tw: '火象', mod_cn: '本位', mod_tw: '本位', ruler_cn: '火星', ruler_tw: '火星' },
        'taurus': { text_cn: '牛', text_tw: '牛', name_cn: '金牛座', name_tw: '金牛座', en: 'Taurus', symbol: '♉', ele_cn: '土象', ele_tw: '土象', mod_cn: '固定', mod_tw: '固定', ruler_cn: '金星', ruler_tw: '金星' },
        'gemini': { text_cn: '双', text_tw: '雙', name_cn: '双子座', name_tw: '雙子座', en: 'Gemini', symbol: '♊', ele_cn: '风象', ele_tw: '風象', mod_cn: '变动', mod_tw: '變動', ruler_cn: '水星', ruler_tw: '水星' },
        'cancer': { text_cn: '蟹', text_tw: '蟹', name_cn: '巨蟹座', name_tw: '巨蟹座', en: 'Cancer', symbol: '♋', ele_cn: '水象', ele_tw: '水象', mod_cn: '本位', mod_tw: '本位', ruler_cn: '月亮', ruler_tw: '月亮' },
        'leo': { text_cn: '狮', text_tw: '獅', name_cn: '狮子座', name_tw: '獅子座', en: 'Leo', symbol: '♌', ele_cn: '火象', ele_tw: '火象', mod_cn: '固定', mod_tw: '固定', ruler_cn: '太阳', ruler_tw: '太陽' },
        'virgo': { text_cn: '处', text_tw: '處', name_cn: '处女座', name_tw: '處女座', en: 'Virgo', symbol: '♍', ele_cn: '土象', ele_tw: '土象', mod_cn: '变动', mod_tw: '變動', ruler_cn: '水星', ruler_tw: '水星' },
        'libra': { text_cn: '秤', text_tw: '秤', name_cn: '天秤座', name_tw: '天秤座', en: 'Libra', symbol: '♎', ele_cn: '风象', ele_tw: '風象', mod_cn: '本位', mod_tw: '本位', ruler_cn: '金星', ruler_tw: '金星' },
        'scorpio': { text_cn: '蝎', text_tw: '蠍', name_cn: '天蝎座', name_tw: '天蠍座', en: 'Scorpio', symbol: '♏', ele_cn: '水象', ele_tw: '水象', mod_cn: '固定', mod_tw: '固定', ruler_cn: '冥王星 / 火星', ruler_tw: '冥王星 / 火星' },
        'sagittarius': { text_cn: '射', text_tw: '射', name_cn: '射手座', name_tw: '射手座', en: 'Sagittarius', symbol: '♐', ele_cn: '火象', ele_tw: '火象', mod_cn: '变动', mod_tw: '變動', ruler_cn: '木星', ruler_tw: '木星' },
        'capricorn': { text_cn: '摩', text_tw: '摩', name_cn: '摩羯座', name_tw: '摩羯座', en: 'Capricorn', symbol: '♑', ele_cn: '土象', ele_tw: '土象', mod_cn: '本位', mod_tw: '本位', ruler_cn: '土星', ruler_tw: '土星' },
        'aquarius': { text_cn: '瓶', text_tw: '瓶', name_cn: '水瓶座', name_tw: '水瓶座', en: 'Aquarius', symbol: '♒', ele_cn: '风象', ele_tw: '風象', mod_cn: '固定', mod_tw: '固定', ruler_cn: '天王星 / 土星', ruler_tw: '天王星 / 土星' },
        'pisces': { text_cn: '鱼', text_tw: '魚', name_cn: '双鱼座', name_tw: '雙魚座', en: 'Pisces', symbol: '♓', ele_cn: '水象', ele_tw: '水象', mod_cn: '变动', mod_tw: '變動', ruler_cn: '海王星 / 木星', ruler_tw: '海王星 / 木星' }
    },
    planets: {
        'su': { text_cn: '日', text_tw: '日', name_cn: '太阳', name_tw: '太陽', en: 'Sun', symbol: '☉' },
        'mo': { text_cn: '月', text_tw: '月', name_cn: '月亮', name_tw: '月亮', en: 'Moon', symbol: '☽' },
        'me': { text_cn: '水', text_tw: '水', name_cn: '水星', name_tw: '水星', en: 'Mercury', symbol: '☿' },
        've': { text_cn: '金', text_tw: '金', name_cn: '金星', name_tw: '金星', en: 'Venus', symbol: '♀' },
        'ma': { text_cn: '火', text_tw: '火', name_cn: '火星', name_tw: '火星', en: 'Mars', symbol: '♂' },
        'ju': { text_cn: '木', text_tw: '木', name_cn: '木星', name_tw: '木星', en: 'Jupiter', symbol: '♃' },
        'sa': { text_cn: '土', text_tw: '土', name_cn: '土星', name_tw: '土星', en: 'Saturn', symbol: '♄' },
        'ur': { text_cn: '天', text_tw: '天', name_cn: '天王星', name_tw: '天王星', en: 'Uranus', symbol: '♅' },
        'ne': { text_cn: '海', text_tw: '海', name_cn: '海王星', name_tw: '海王星', en: 'Neptune', symbol: '♆' },
        'pl': { text_cn: '冥', text_tw: '冥', name_cn: '冥王星', name_tw: '冥王星', en: 'Pluto', symbol: '♇' },
        'asc': { text_cn: '升', text_tw: '升', name_cn: '上升点', name_tw: '上升點', en: 'Ascendant', symbol: 'ASC' },
        'mc': { text_cn: '中', text_tw: '中', name_cn: '天顶', name_tw: '天頂', en: 'Midheaven', symbol: 'MC' },
        'ic': { text_cn: '底', text_tw: '底', name_cn: '天底', name_tw: '天底', en: 'Imum Coeli', symbol: 'IC' },
        'dsc': { text_cn: '降', text_tw: '降', name_cn: '下降点', name_tw: '下降點', en: 'Descendant', symbol: 'DES' },
        'dc': { text_cn: '降', text_tw: '降', name_cn: '下降点', name_tw: '下降點', en: 'Descendant', symbol: 'DES' },
        'vx': { text_cn: '宿', text_tw: '宿', name_cn: '宿命点', name_tw: '宿命點', en: 'Vertex', symbol: 'Vx' },
        'tn': { text_cn: '北', text_tw: '北', name_cn: '北交点', name_tw: '北交點', en: 'True Node', symbol: '☊' },
        'sn': { text_cn: '南', text_tw: '南', name_cn: '南交点', name_tw: '南交點', en: 'South Node', symbol: '☋' },
        'pfo': { text_cn: '福', text_tw: '福', name_cn: '福点', name_tw: '福點', en: 'Part of Fortune', symbol: '⊗' },
        'ch': { text_cn: '凯', text_tw: '凱', name_cn: '凯龙星', name_tw: '凱龍星', en: 'Chiron', symbol: '⚷' },
        'ce': { text_cn: '谷', text_tw: '穀', name_cn: '谷神星', name_tw: '穀神星', en: 'Ceres', symbol: '⚳' },
        'pa': { text_cn: '智', text_tw: '智', name_cn: '智神星', name_tw: '智神星', en: 'Pallas', symbol: '⚴' },
        'jo': { text_cn: '婚', text_tw: '婚', name_cn: '婚神星', name_tw: '婚神星', en: 'Juno', symbol: '⚵' },
        'vt': { text_cn: '灶', text_tw: '灶', name_cn: '灶神星', name_tw: '灶神星', en: 'Vesta', symbol: '⚶' }
    }
};

// ==========================================
// 🌟 2. 注入悬浮特效 CSS (优化数据框排版)
// ==========================================
jQuery(document).ready(function($) {
    if ($('#yfj-astro-styles').length === 0) {
        $('<style id="yfj-astro-styles">').text(`
            .yfj-astro-hoverable { 
                cursor: pointer; 
                transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), filter 0.2s ease; 
                transform-origin: center center;
                transform-box: fill-box;
            }
            .yfj-astro-hoverable:hover { 
                transform: scale(1.6); 
                filter: drop-shadow(0px 0px 4px rgba(200, 150, 80, 0.8)); 
                z-index: 999;
            }
            #yfj-astro-tooltip {
                position: absolute; display: none; background: rgba(15, 23, 42, 0.95);
                color: #f8fafc; padding: 12px 16px; border-radius: 8px;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                font-size: 13px; line-height: 1.8;
                box-shadow: 0 8px 25px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15);
                pointer-events: none; z-index: 999999; min-width: 160px;
                backdrop-filter: blur(4px);
            }
            #yfj-astro-tooltip .tt-title { font-weight: bold; font-size: 16px; color: #fbbf24; border-bottom: 1px dashed rgba(255,255,255,0.2); padding-bottom: 6px; margin-bottom: 8px; display: flex; align-items: baseline; gap: 6px; }
            #yfj-astro-tooltip .tt-row { display: flex; justify-content: space-between; gap: 20px; }
            #yfj-astro-tooltip .tt-label { color: #94a3b8; }
            #yfj-astro-tooltip .tt-val-highlight { color: #fff; font-weight: 500; }
            
            /* 🌟 新增：相位连线高亮特效 */
            .yfj-aspect-line {
                transition: stroke-width 0.2s ease, filter 0.2s ease, opacity 0.2s ease, stroke 0.2s ease;
                cursor: crosshair; /* 鼠标变成十字准星，感觉更专业 */
            }
            /* 悬浮单条线时：加粗、提亮、加发光滤镜 */
            .yfj-aspect-line:hover {
                stroke-width: 3.5px !important; /* 强制加粗 */
                filter: drop-shadow(0px 0px 5px rgba(255,255,255,0.9)) brightness(1.3); /* 发光特效 */
                opacity: 1 !important;
            }
            /* 🌟 拔高体验：当鼠标移到相位图层上时，未被 hover 的线自动变暗，凸显你正在看的那条线 */
            .yfj-aspects-group:hover .yfj-aspect-line:not(:hover) {
                opacity: 0.2 !important;
            }
        `).appendTo('head');
    }

    if ($('#yfj-astro-tooltip').length === 0) {
        $('body').append('<div id="yfj-astro-tooltip"></div>');
    }
});

// ==========================================
// 🌟 3. 黄经转黄道度数算法
// ==========================================
function convertLongitudeToSignInfo(longitude, isTW) {
    const signsList = isTW
        ? ['白羊座', '金牛座', '雙子座', '巨蟹座', '獅子座', '處女座', '天秤座', '天蠍座', '射手座', '摩羯座', '水瓶座', '雙魚座']
        : ['白羊座', '金牛座', '双子座', '巨蟹座', '狮子座', '处女座', '天秤座', '天蝎座', '射手座', '摩羯座', '水瓶座', '双鱼座'];

    let val = parseFloat(longitude);
    if (isNaN(val)) return null;
    val = (val % 360 + 360) % 360;
    let signIndex = Math.floor(val / 30);
    let degreesRaw = val % 30;
    let degrees = Math.floor(degreesRaw);
    let minutes = Math.floor((degreesRaw - degrees) * 60);
    let minStr = minutes < 10 ? '0' + minutes : minutes;
    return { signName: signsList[signIndex], position: `${degrees}°${minStr}'` };
}

// ==========================================
// 🌟 4. 核心渲染引擎
// ==========================================
function YFJ_Render_Astro_Icons($resultContainer) {
    let isTW = (typeof yfj_globals !== 'undefined' && yfj_globals.lang === 'zh-tw');
    let styleKey = (typeof yfj_globals !== 'undefined' && yfj_globals.icon_style) ? yfj_globals.icon_style : 'text';

    let textDictKey = isTW ? 'text_tw' : 'text_cn';
    let nameDictKey = isTW ? 'name_tw' : 'name_cn';

    function resolveKey(text, dataCode) {
        text = (text || '').trim();
        dataCode = (dataCode || '').trim().toLowerCase();
        if (dataCode && YFJ_Astro_Dict.planets[dataCode]) return dataCode;
        const unicodeMap = {
            '♈': 'aries', '♉': 'taurus', '♊': 'gemini', '♋': 'cancer', '♌': 'leo', '♍': 'virgo', '♎': 'libra', '♏': 'scorpio', '♐': 'sagittarius', '♑': 'capricorn', '♒': 'aquarius', '♓': 'pisces',
            '☉': 'su', '☽': 'mo', '☾': 'mo', '☿': 'me', '♀': 've', '♂': 'ma', '♃': 'ju', '♄': 'sa', '♅': 'ur', '♆': 'ne', '♇': 'pl', '⚷': 'ch', '☊': 'tn', '☋': 'sn', '⊗': 'pfo', '⊕': 'pfo'
        };
        if (unicodeMap[text]) return unicodeMap[text];
        return text.toLowerCase();
    }

    $resultContainer.find('.astrology-chart text[data-label-part="position"]').hide();

    // 替换外圈大星座
    $resultContainer.find('.astrology-chart text[data-type="zodiac-sign"]').each(function() {
        let key = (jQuery(this).attr('data-sign-name') || '').toLowerCase();
        if (YFJ_Astro_Dict.signs[key]) {
            let displayChar = styleKey === 'symbol' ? YFJ_Astro_Dict.signs[key].symbol : YFJ_Astro_Dict.signs[key][textDictKey];
            jQuery(this).text(displayChar)
                .addClass('yfj-custom-text-icon yfj-astro-hoverable')
                .attr('data-astro-type', 'sign')
                .attr('data-astro-key', key)
                .attr('font-size', styleKey === 'symbol' ? '28px' : '20px')
                .attr('font-family', 'sans-serif');
        }
    });

    // 替换内圈主行星
    $resultContainer.find('.astrology-chart text[data-label-part="symbol"]').each(function() {
        let rawText = jQuery(this).text();
        let parentCode = jQuery(this).closest('[data-code]').attr('data-code');
        let key = resolveKey(rawText, parentCode);

        if (YFJ_Astro_Dict.planets[key]) {
            let displayChar = styleKey === 'symbol' ? YFJ_Astro_Dict.planets[key].symbol : YFJ_Astro_Dict.planets[key][textDictKey];
            jQuery(this).text(displayChar)
                .addClass('yfj-custom-text-icon yfj-astro-hoverable')
                .attr('data-astro-type', 'planet')
                .attr('data-astro-key', key)
                .attr('font-size', styleKey === 'symbol' ? '18px' : '15px')
                .attr('font-family', 'sans-serif');
        }
    });

    // 替换微缩星座
    $resultContainer.find('.astrology-chart text[data-label-part="sign"]').each(function() {
        let rawText = jQuery(this).text();
        let key = resolveKey(rawText, '');
        if (YFJ_Astro_Dict.signs[key]) {
            let displayChar = styleKey === 'symbol' ? YFJ_Astro_Dict.signs[key].symbol : YFJ_Astro_Dict.signs[key][textDictKey];
            jQuery(this).text(displayChar)
                .addClass('yfj-custom-text-icon yfj-astro-hoverable')
                .attr('data-astro-type', 'sign')
                .attr('data-astro-key', key)
                .attr('font-size', styleKey === 'symbol' ? '14px' : '12px')
                .attr('font-family', 'sans-serif');

            let currentX = parseFloat(jQuery(this).attr('x'));
            let anchor = jQuery(this).attr('text-anchor');
            let shift = anchor === 'end' ? 15 : -15;
            jQuery(this).attr('x', currentX + shift);
        }
    });

    // 🌟🌟🌟 新增：拦截并处理相位线的高亮特效 🌟🌟🌟
    // 注意：这里的选择器尽可能覆盖了主流 SVG 生成器的相位线标签。
    // 如果你的 SVG 里的相位线是 <line> 并且在特定的 <g> 里，或者自带了 data-type="aspect"
    let $aspectLines = $resultContainer.find('.astrology-chart line[data-type="aspect"], .astrology-chart g.aspects line, .astrology-chart g[data-type="aspects"] line, .astrology-chart .aspect-line');

    if ($aspectLines.length > 0) {
        // 给线条加上高亮类
        $aspectLines.addClass('yfj-aspect-line');
        // 给线条的父容器（通常是 <g>）加上组类，用于实现“一条高亮，其他变暗”的极客特效
        $aspectLines.parent().addClass('yfj-aspects-group');
    }

    // ==========================================
    // 🌟 5. 绑定究极形态的数据外挂事件
    // ==========================================
    let $tooltip = jQuery('#yfj-astro-tooltip');

    $resultContainer.find('.yfj-astro-hoverable').on('mouseenter', function(e) {
        let type = jQuery(this).attr('data-astro-type');
        let key = jQuery(this).attr('data-astro-key');
        let htmlStr = '';

        let lblSign = isTW ? '落在：' : '落在：';
        let lblHouse = isTW ? '宮位：' : '宫位：';
        let lblStatus = isTW ? '狀態：' : '状态：';
        let lblEle = isTW ? '元素：' : '元素：';
        let lblMod = isTW ? '模式：' : '模式：';
        let lblRuler = isTW ? '守護星：' : '守护星：';
        let lblAbsLong = isTW ? '絕對黃經：' : '绝对黄经：';

        if (type === 'planet') {
            let $parentGroup = jQuery(this).closest('[data-type="planet"]');

            let personSuffix = '';
            if ($parentGroup.hasClass('planet-person-a') || $parentGroup.closest('[data-person="a"]').length) {
                personSuffix = isTW ? ' <span style="color:#fbbf24; font-size:12px;">(A盤/本命)</span>' : ' <span style="color:#fbbf24; font-size:12px;">(A盘/本命)</span>';
            } else if ($parentGroup.hasClass('planet-person-b') || $parentGroup.closest('[data-person="b"]').length) {
                personSuffix = isTW ? ' <span style="color:#60a5fa; font-size:12px;">(B盤/行運)</span>' : ' <span style="color:#60a5fa; font-size:12px;">(B盘/行运)</span>';
            }

            let longitude = $parentGroup.attr('data-longitude');
            let house = $parentGroup.attr('data-house');
            let isRetrograde = $parentGroup.attr('data-is-retrograde') === 'true';

            // 🔪 核心修复：彻底无视 SVG 内部那不可靠的文本度数，防止张冠李戴！
            let planetName = YFJ_Astro_Dict.planets[key][nameDictKey];
            let planetEn = YFJ_Astro_Dict.planets[key].en;

            htmlStr += `<div class="tt-title">${YFJ_Astro_Dict.planets[key].symbol} ${planetName} ${personSuffix} <span style="font-size:13px; color:#94a3b8; font-weight:normal;">(${planetEn})</span></div>`;

            // 100% 依赖底层 data-longitude 进行黄经换算，绝对不可能出错
            let posInfo = convertLongitudeToSignInfo(longitude, isTW);
            if (posInfo) {
                htmlStr += `<div class="tt-row"><span class="tt-label">${lblSign}</span><span class="tt-val-highlight">${posInfo.signName} ${posInfo.position}</span></div>`;
            }

            if (house) {
                let houseVal = isTW ? `第 ${house} 宮` : `第 ${house} 宫`;
                htmlStr += `<div class="tt-row"><span class="tt-label">${lblHouse}</span><span class="tt-val-highlight">${houseVal}</span></div>`;
            }
            if (isRetrograde) {
                htmlStr += `<div class="tt-row"><span class="tt-label">${lblStatus}</span><span style="color:#ef4444; font-weight:bold;">逆行 (Rx)</span></div>`;
            }

            let dignity = $parentGroup.attr('data-dignity') || $parentGroup.attr('data-status');
            if (dignity) {
                htmlStr += `<div class="tt-row"><span class="tt-label">${lblStatus}</span><span style="color:#10b981;">${dignity}</span></div>`;
            }

            if (longitude) {
                let longVal = parseFloat(longitude).toFixed(2) + '°';
                htmlStr += `<div class="tt-row"><span class="tt-label">${lblAbsLong}</span><span>${longVal}</span></div>`;
            }

        } else if (type === 'sign') {
            let signObj = YFJ_Astro_Dict.signs[key];
            let signName = signObj[nameDictKey];
            let signEn = signObj.en;

            let eleKey = isTW ? 'ele_tw' : 'ele_cn';
            let modKey = isTW ? 'mod_tw' : 'mod_cn';
            let rulerKey = isTW ? 'ruler_tw' : 'ruler_cn';

            htmlStr += `<div class="tt-title" style="margin-bottom:10px;">${signObj.symbol} ${signName} <span style="font-size:13px; color:#94a3b8; font-weight:normal;">(${signEn})</span></div>`;
            htmlStr += `<div class="tt-row"><span class="tt-label">${lblEle}</span><span class="tt-val-highlight">${signObj[eleKey]}</span></div>`;
            htmlStr += `<div class="tt-row"><span class="tt-label">${lblMod}</span><span class="tt-val-highlight">${signObj[modKey]}</span></div>`;
            htmlStr += `<div class="tt-row"><span class="tt-label">${lblRuler}</span><span class="tt-val-highlight">${signObj[rulerKey]}</span></div>`;
        }

        $tooltip.html(htmlStr).fadeIn(150);
    });

    $resultContainer.find('.yfj-astro-hoverable').on('mousemove', function(e) {
        $tooltip.css({ top: e.pageY + 15 + 'px', left: e.pageX + 15 + 'px' });
    });

    $resultContainer.find('.yfj-astro-hoverable').on('mouseleave', function() {
        $tooltip.hide();
    });
}

// ==========================================
// 🚀 6. 表单提交与拦截主逻辑
// ==========================================
jQuery(document).ready(function($) {
    $('.yfj-ajax-form').on('submit', function(e) {
        e.preventDefault();

        let $form = $(this);
        let $container = $form.closest('.yfj-form-container');
        let module_id = $container.data('module');
        let $loading = $container.find('.yfj-loading');
        let $result = $container.find('.yfj-result-area');

        let nonce = $form.find('input[name="yfj_nonce_field"]').val();

        let formDataArray = $form.serializeArray();
        let businessData = {};
        $.each(formDataArray, function() {
            if(this.name !== 'yfj_nonce_field' && this.name !== '_wp_http_referer') {
                businessData[this.name] = this.value;
            }
        });

        $form.hide();
        $loading.show();
        $result.hide().empty();

        $.ajax({
            url: yfj_globals.ajax_url,
            type: 'POST',
            data: {
                action: 'yfj_action_' + module_id,
                nonce: nonce,
                form_data: businessData
            },
            success: function(res) {
                $loading.hide();
                $form.show();
                if(res.success) {
                    $result.html(res.data.html);

                    if ($result.find('.astrology-chart').length > 0) {
                        YFJ_Render_Astro_Icons($result);
                    }

                    $result.fadeIn();
                } else {
                    $result.html('<div style="color:red;">' + yfj_globals.err_prefix + res.data + '</div>').fadeIn();
                }
            },
            error: function() {
                $loading.hide();
                $form.show();
                $result.html('<div style="color:red;">' + yfj_globals.err_net + '</div>').fadeIn();
            }
        });
    });
});