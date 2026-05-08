<?php
/*
Plugin Name: Chinese Astrology & Divination Toolkit – Bazi & Ziwei Tools
Description: WordPress astrology toolkit for Bazi (Four Pillars), Ziwei Dou Shu, Chinese astrology charts, divination, and daily horoscope tools. Supports shortcode integration, sandbox/live mode, and multilingual output (Simplified Chinese, Traditional Chinese).
Version: 2.0.2
Author: Yuanfenju
Author URI: https://doc.yuanfenju.com
Text Domain: yuanfenju-astrology-toolkit
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define('YFJ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YFJ_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once YFJ_PLUGIN_DIR . 'includes/class-base-module.php';

class Yuanfenju_Astrology_Toolkit {

    // 【核心战略升级】采用“流量权重 + 用户路径”的产品结构矩阵
    private $module_categories = [

        // 🟢 八字排盘与传统择吉工具
        'core_primary' => [
            'title' => '🟢 八字排盘与传统择吉工具',
            'desc'  => '提供八字排盘、测算与合婚分析，并支持老黄历与择吉日查询，用于基础命理分析与日常择日参考。',
            'modules' => [
                // 八字核心流量
                'bazi_paipan'   => '八字排盘',
                'bazi_cesuan'   => '八字测算',
                'bazi_jingpan'  => '八字流盘',
                'bazi_jingsuan' => '八字精算',

                'bazi_hehun'    => '八字合婚',
                'bazi_hepan'    => '八字合盘',

                //单独强化
                'gongju_laohuangli'  => '老黄历',
                'gongju_zeshi'  => '择吉日',
            ]
        ],

        // 🟡 紫微斗数与命理运势系统
        'core_advanced' => [
            'title' => '🟡 紫微斗数与命理运势系统',
            'desc'  => '提供紫微斗数排盘与流盘分析，并扩展每日运势、生肖运势与流年财运等进阶命理内容，用于更深入的命理分析。',
            'modules' => [
                'bazi_zwpan'    => '紫微斗数排盘',
                'bazi_zwlpan'   => '紫微斗数流盘',

                'zhanbu_xingzuo'   => '星座每日运势',
                'zhanbu_shengxiao' => '生肖每日运势',
                'bazi_yunshi' => '八字每日运势',
                'bazi_caiyunfenxi'=> '流年财运分析',
            ]
        ],

        // 🔵 传统术数与占卜工具
        'divination_system' => [
            'title' => '🔵 传统术数与占卜工具',
            'desc'  => '提供奇门遁甲、六壬、梅花易数、六爻等传统术数排盘工具，适用于进阶命理研究与专业分析使用。',
            'modules' => [
                'liupan_qimendunjia'   => '奇门遁甲排盘',
                'liupan_yinpanqimen'   => '阴盘奇门',
                'liupan_daliuren'      => '大六壬排盘',
                'liupan_meihua'        => '梅花易数',
                'liupan_liuyao'        => '六爻排盘',
                'liupan_jinkoujue'     => '金口诀',
                'zhanbu_yaogua'        => '摇卦占卜',
                'fengshui_xuankong'      => '玄空飞星',
            ]
        ],

        // 🟣 灵签与娱乐占卜工具
        'entertainment' => [
            'title' => '🟣 灵签与娱乐占卜工具',
            'desc'  => '提供塔罗、灵签与每日占卜等轻量娱乐型预测工具，适用于日常占卜与兴趣体验。',
            'modules' => [
                'zhanbu_taluojiedu' => '塔罗牌解读',
                'zhanbu_meiri'      => '每日一占',

                // 灵签系统
                'lingqian_fozu'   => '佛祖灵签',
                'lingqian_lvzu'   => '吕祖灵签',
                'lingqian_mazu'   => '妈祖灵签',
                'lingqian_yuelao' => '月老灵签',
                'lingqian_guanyin'=> '观音灵签',
                'lingqian_zhuge'  => '诸葛灵签',
            ]
        ],
    ];

    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_yfj_create_page', [$this, 'ajax_create_page']);

        $plugin_basename = plugin_basename(__FILE__);
        add_filter("plugin_action_links_{$plugin_basename}", [$this, 'add_plugin_settings_link']);

        $this->load_active_modules();
    }

    // 获取当前配置矩阵中的所有模块 Key
    private function get_all_module_keys() {
        $keys = [];
        foreach ($this->module_categories as $category) {
            if (!empty($category['modules'])) {
                $keys = array_merge($keys, array_keys($category['modules']));
            }
        }
        return $keys;
    }

    private function load_active_modules() {
        $default_modules = $this->get_all_module_keys();
        $active_modules = get_option('yfj_active_modules', $default_modules);

        if(empty($active_modules) || !is_array($active_modules)) return;
        foreach ($active_modules as $mod) {
            $file = YFJ_PLUGIN_DIR . "modules/{$mod}/class-{$mod}.php";
            if (file_exists($file)) {
                require_once $file;
                $class_name = 'YFJ_Module_' . ucfirst($mod);
                if (class_exists($class_name)) { new $class_name(); }
            }
        }
    }

    public function enqueue_assets() {
        wp_enqueue_style('yfj-style', YFJ_PLUGIN_URL . 'assets/css/style.css', [], '2.0.2');
        wp_enqueue_script('yfj-ajax', YFJ_PLUGIN_URL . 'assets/js/yuanfenju-ajax.js', ['jquery'], '2.0.2', true);

        $lang = get_option('yfj_language', 'zh-cn');
        wp_localize_script('yfj-ajax', 'yfj_globals', [
            'ajax_url'   => admin_url('admin-ajax.php'),
            'err_prefix' => $lang === 'zh-tw' ? '錯誤: ' : '错误: ',
            'err_net'    => $lang === 'zh-tw' ? '網絡請求失敗，請稍後再試。' : '网络请求失败，请稍后再试。'
        ]);

        $theme_color = get_option('yfj_theme_color', '#c99a5b');
        $dynamic_css = ":root { --yfj-primary: {$theme_color} !important; --yfj-primary-hover: color-mix(in srgb, {$theme_color} 85%, black) !important; }";
        wp_add_inline_style('yfj-style', $dynamic_css);
    }

    public function admin_menu() {
        add_menu_page('缘份居', '缘份居', 'manage_options', 'yuanfenju-platform', [$this, 'settings_page'], 'dashicons-admin-site-alt3', 55);
    }

    public function register_settings() {
        register_setting('yfj_settings', 'yfj_environment');
        register_setting('yfj_settings', 'yfj_api_key', [
            'sanitize_callback' => [$this, 'validate_api_settings'],
        ]);
        register_setting('yfj_settings', 'yfj_language');
        register_setting('yfj_settings', 'yfj_active_modules');
        register_setting('yfj_settings', 'yfj_theme_color', 'sanitize_hex_color');

        //注册安全等级变量
        register_setting('yfj_settings', 'yfj_security_level');
    }

    public function validate_api_settings($input) {
        $env = $_POST['yfj_environment'] ?? get_option('yfj_environment');
        $api_key = trim($input);
        if ($env === 'live' && empty($api_key)) {
            add_settings_error('yfj_api_key', 'api_key_error', '保存失败：在 Live (生产环境) 下，必须填写 API Key。', 'error');
            return get_option('yfj_api_key');
        }
        return sanitize_text_field($api_key);
    }

    public function settings_page() {
        $env = get_option('yfj_environment', 'sandbox');
        $lang = get_option('yfj_language', 'zh-cn');
        //获取安全等级，默认为
        $security_level = get_option('yfj_security_level', '5');

        $default_modules = $this->get_all_module_keys();
        $active = get_option('yfj_active_modules', $default_modules);

        $status_badge = $env === 'live'
            ? '<span style="background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:6px; font-size:13px; font-weight:bold; vertical-align:middle; margin-left:10px;"><span class="dashicons dashicons-admin-network" style="font-size:16px; line-height:1.2;"></span> 🟢 Live (真实运行中)</span>'
            : '<span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:6px; font-size:13px; font-weight:bold; vertical-align:middle; margin-left:10px;"><span class="dashicons dashicons-shield" style="font-size:16px; line-height:1.2;"></span> 🟡 Sandbox (安全测试中)</span>';
        ?>
        <div class="wrap">
            <h1 style="display: flex; align-items: center;">缘份居 Astrology Toolkit 配置 <?php echo $status_badge; ?></h1>

            <?php
            // 探针：检测服务器 SSL 能力
            $curl_info = curl_version();
            $ssl_version = $curl_info['ssl_version'] ?? '未知';
            $is_ssl_old = false;

            // 简单判断：如果 OpenSSL 版本低于 1.0.1，大概率不支持 TLS 1.2
            if (preg_match('/OpenSSL\/0\./i', $ssl_version) || preg_match('/OpenSSL\/1\.0\.0/i', $ssl_version)) {
                $is_ssl_old = true;
            }
            ?>

            <?php if ($is_ssl_old): ?>
                <div class="notice notice-error" style="margin-top:20px; border-left-color: #dc2626; background: #fef2f2;">
                    <p>
                        <span class="dashicons dashicons-warning" style="color: #dc2626;"></span>
                        <strong>严重环境警告：您的服务器底层组件过旧！</strong><br>
                        检测到您的 cURL SSL 版本为 <code><?php echo esc_html($ssl_version); ?></code>。该版本极大概率不支持现代互联网强制要求的 TLS 1.2 安全协议。<br>
                        这会导致插件**无法连接到缘份居 API，出现网络请求失败或超时**。请联系您的服务器商，将 <strong>PHP、cURL 及 OpenSSL</strong> 升级至较新版本。
                    </p>
                </div>
            <?php endif; ?>

            <?php settings_errors(); ?>

            <div class="notice notice-info is-dismissible" style="margin-top:20px; border-left-color: #c99a5b; background: #fff;">
                <p>
                    <strong>💡 底层算法支持：</strong>
                    本插件的全部命理测算数据均由 <strong>缘份居</strong> 提供实时计算。如果您是开发者，想要了解更多功能逻辑，请查阅
                    <a href="https://doc.yuanfenju.com" target="_blank" style="font-weight:bold; color:#c99a5b; text-decoration:none;">
                        <span class="dashicons dashicons-external" style="font-size:16px; vertical-align:middle;"></span> 缘份居国学 API 官方文档
                    </a>
                </p>
            </div>

            <form method="post" action="options.php">
                <?php settings_fields('yfj_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th>运行环境</th>
                        <td>
                            <select name="yfj_environment" style="min-width: 300px;">
                                <option value="sandbox" <?php selected($env, 'sandbox'); ?>>🟡 Sandbox (安全沙盒 - 免费模拟测试环境)</option>
                                <option value="live" <?php selected($env, 'live'); ?>>🟢 Live (生产环境 - 扣除额度并返回真实测算)</option>
                            </select>
                            <p class="description" style="color:#16a34a; margin-top:8px;">
                                <span class="dashicons dashicons-privacy" style="font-size:16px; line-height:1.3;"></span> <strong>隐私合规保障：</strong> 本系统采用无用户数据存储设计，测算数据即用即毁。
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>API Key<br><small style="color:#64748b;">(Live 环境必须配置)</small></th>
                        <td>
                            <input type="text" name="yfj_api_key" value="<?php echo esc_attr(get_option('yfj_api_key')); ?>" class="regular-text" placeholder="请输入 API Key" style="min-width: 300px;"/>
                            <div style="margin-top:12px; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; max-width: 600px;">
                                <?php if (empty(get_option('yfj_api_key'))): ?>
                                    <div style="color:#b91c1c; font-weight:600; margin-bottom: 8px;"><span class="dashicons dashicons-warning"></span> 未检测到 API Key，无法启用 Live 真实测算！</div>
                                    <a href="https://portal.yuanfenju.com/Merchant/Public/register" target="_blank" class="button button-primary" style="background:#c99a5b; border-color:#c99a5b; text-shadow:none;">免费获取 API Key</a>
                                <?php else: ?>
                                    <div style="color:#15803d; font-weight:600; margin-bottom: 8px;"><span class="dashicons dashicons-yes-alt"></span> API Key 已配置，接口通讯就绪。</div>

                                    <?php
                                    if ($env === 'live') {
                                        $api_key = get_option('yfj_api_key');
                                        $response = wp_remote_post('https://api.yuanfenju.com/index.php/v1/Free/querymerchant', [
                                            'body'      => ['api_key' => $api_key],
                                            'timeout'   => 15,    // 增加超时容错
                                            'sslverify' => false  // 绕过老旧服务器的 SSL 证书链验证
                                        ]);

                                        if (!is_wp_error($response)) {
                                            $body = json_decode(wp_remote_retrieve_body($response), true);
                                            if (isset($body['errcode']) && $body['errcode'] == 0) {
                                                $merchant_data = $body['data'];
                                                $m_type = $merchant_data['merchant_type'] ?? '未知';
                                                $m_expire = $merchant_data['merchant_expire_time'] ?? '--';
                                                $m_remain = $merchant_data['merchant_remaining_call_times'] ?? '--';

                                                echo '<div style="margin-top:15px; padding:15px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; color:#166534; font-size:13px; line-height:1.8;">';
                                                echo '<strong style="display:block; margin-bottom:8px; font-size:14px; border-bottom:1px dashed #bbf7d0; padding-bottom:8px;">';
                                                echo '<span class="dashicons dashicons-businessman" style="vertical-align:middle;"></span> 商户状态概览</strong>';
                                                echo '<div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">';
                                                echo '<div><strong>账号类型：</strong> ' . esc_html($m_type) . '</div>';

                                                if ($m_type === '包年会员') {
                                                    echo '<div><strong>过期时间：</strong> <span style="color:#b91c1c; font-weight:bold;">' . esc_html($m_expire) . '</span></div>';

                                                    $res_times = wp_remote_post('https://api.yuanfenju.com/index.php/v1/Free/querytimes', [
                                                        'body'      => ['api_key' => $api_key],
                                                        'timeout'   => 15,
                                                        'sslverify' => false
                                                    ]);
                                                    if (!is_wp_error($res_times)) {
                                                        $body_times = json_decode(wp_remote_retrieve_body($res_times), true);
                                                        if (isset($body_times['errcode']) && $body_times['errcode'] == 0) {
                                                            $call_times = $body_times['data']['call_times'] ?? '0';
                                                            $msg = $body_times['data']['expire_time_message'] ?? '';
                                                            echo '<div style="grid-column: 1 / -1;"><strong>今日调用：</strong> <span style="font-weight:bold; font-size:14px;">' . esc_html($call_times) . '</span> 次 <span style="opacity:0.8; font-size:12px; margin-left:5px;">(' . esc_html($msg) . ')</span></div>';
                                                        }
                                                    }
                                                } else {
                                                    echo '<div><strong>剩余额度：</strong> <span style="color:#b91c1c; font-weight:bold; font-size:14px;">' . esc_html($m_remain) . '</span> 次</div>';
                                                }
                                                echo '</div></div>';
                                            } else {
                                                echo '<div style="margin-top:10px; padding:10px; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:4px;"><span class="dashicons dashicons-warning"></span> 账户查询失败：' . esc_html($body['errmsg'] ?? 'API Key 无效或已过期') . '</div>';
                                            }
                                        } else {
                                            echo '<div style="margin-top:10px; padding:10px; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:4px;"><span class="dashicons dashicons-warning"></span> 网络连接超时，无法获取账户状态。</div>';
                                        }
                                    } else {
                                        echo '<div style="color:#475569; font-size: 13px;">💡 <strong>提示：</strong> 当前为 Sandbox 模式。切换到 Live 模式并保存后，即可在此处查看您的实时账户余额与会员状态。</div>';
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>防盗刷安全等级</th>
                        <td>
                            <select name="yfj_security_level" style="min-width: 300px;">
                                <option value="5" <?php selected($security_level, '5'); ?>>🟡 推荐 (1分钟 5次) - 平衡体验与安全</option>
                                <option value="10" <?php selected($security_level, '10'); ?>>🟢 宽松 (1分钟 10次) - 适合共享 IP 较多的环境</option>
                                <option value="3" <?php selected($security_level, '3'); ?>>🔴 严格 (1分钟 3次) - 强力防范恶意请求</option>
                            </select>
                            <p class="description" style="color:#64748b; margin-top:8px;">
                                自动拦截机器爬虫和脚本恶意疯狂测算，由系统底层强制生效，有效保护您的 API 余额不被盗刷。
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>测算结果语言</th>
                        <td>
                            <select name="yfj_language" style="min-width: 300px;">
                                <option value="zh-cn" <?php selected($lang, 'zh-cn'); ?>>简体中文 (zh-cn)</option>
                                <option value="zh-tw" <?php selected($lang, 'zh-tw'); ?>>繁体中文 (zh-tw)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>界面主题色</th>
                        <td>
                            <input type="color" name="yfj_theme_color" value="<?php echo esc_attr(get_option('yfj_theme_color', '#c99a5b')); ?>" style="cursor: pointer; height: 35px; width: 60px; vertical-align: middle;" />
                            <span class="description" style="margin-left: 8px;">自定义前端测算界面的主色调。</span>
                        </td>
                    </tr>

                    <!-- 【核心战略升级】：按梯队分层展示勾选模块 -->
                    <tr>
                        <th>功能矩阵配置<br><small style="color:#64748b; font-weight:normal;">(开启所需功能)</small></th>
                        <td>
                            <?php $admin_nonce = wp_create_nonce('yfj_admin_nonce'); ?>

                            <div style="max-width: 680px; display: flex; flex-direction: column; gap: 20px;">
                                <?php foreach($this->module_categories as $cat_id => $category): ?>
                                    <div style="background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); overflow: hidden;">
                                        <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0;">
                                            <h4 style="margin: 0; font-size: 14px; color: #0f172a;"><?php echo esc_html($category['title']); ?></h4>
                                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;"><?php echo esc_html($category['desc']); ?></p>
                                        </div>
                                        <div style="padding: 16px; display: flex; flex-direction: column; gap: 12px;">
                                            <?php if(empty($category['modules'])): ?>
                                                <div style="color: #94a3b8; font-size: 13px; text-align: center; padding: 10px 0;">此梯队功能开发中，敬请期待...</div>
                                            <?php else: ?>
                                                <?php foreach($category['modules'] as $key => $name): ?>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <label style="display: flex; align-items: center; gap: 8px; flex: 1; cursor: pointer;">
                                                            <input type="checkbox" name="yfj_active_modules[]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $active)); ?>>
                                                            <strong style="font-size: 14px; color: #334155;"><?php echo esc_html($name); ?></strong>
                                                        </label>
                                                        <code id="yfj_sc_<?php echo esc_attr($key); ?>" style="background: #f1f5f9; padding: 4px 8px; font-size: 12px; border-radius: 4px; color: #475569;">[yfj_<?php echo esc_html($key); ?>]</code>
                                                        <button type="button" class="button button-small" onclick="yfjCopyShortcode('yfj_sc_<?php echo esc_attr($key); ?>', this)">复制</button>
                                                        <button type="button" class="button button-small button-primary" onclick="yfjAutoCreatePage('<?php echo esc_js($key); ?>', '<?php echo esc_js($name); ?>', '<?php echo $admin_nonce; ?>', this)" style="background:#c99a5b; border-color:#c99a5b; text-shadow:none;">自动建页</button>
                                                    </div>
                                                    <div id="yfj_actions_<?php echo esc_attr($key); ?>" style="display: none; font-size: 12px; padding-left: 28px; margin-top: -6px;"></div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div style="margin-top: 15px; background: #f0fdf4; border-left: 3px solid #22c55e; padding: 12px 15px; max-width: 650px;">
                                <strong>💡 引导提示：</strong><br>
                                <span style="color: #166534; font-size: 13px; line-height: 1.6;">
                                    👉 勾选上方模块并保存后，点击 <b>[一键生成页面]</b> 即可自动建好独立专属页。<br>
                                    👉 您也可以点击 <b>[复制]</b> 将简码粘贴到任何页面构建器（如 Elementor）中。<br>
                                    <span style="color: #dc2626; margin-top: 5px; display: inline-block;">
        ⚠️ <b>温馨提示：一个页面请只放置一个功能简码。</b>请勿把多个功能（如八字排盘和塔罗占卜）放在同一个页面，否则会引起地点、时间等选项互相干扰，导致测算无法正常使用。</span>
                                </span>
                            </div>
                        </td>
                    </tr>
                </table>
                <?php submit_button('保存平台配置', 'primary', 'submit', true, ['style' => 'background:#0f172a; border-color:#0f172a; text-shadow:none; padding: 0 30px;']); ?>
            </form>
        </div>

        <script>
            function yfjCopyShortcode(elementId, btn) {
                var textToCopy = document.getElementById(elementId).innerText;
                var textArea = document.createElement("textarea");
                textArea.value = textToCopy;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                textArea.remove();
                var originalHtml = btn.innerHTML;
                btn.innerHTML = '已复制';
                btn.style.color = '#46b450';
                setTimeout(function() { btn.innerHTML = originalHtml; btn.style.color = ''; }, 2000);
            }

            function yfjAutoCreatePage(moduleKey, moduleName, nonce, btn) {
                if(!confirm('系统将为您创建“' + moduleName + '”的独立页面，是否继续？')) return;
                var originalHtml = btn.innerHTML;
                btn.innerHTML = '生成中...';
                btn.disabled = true;
                jQuery.post(ajaxurl, {
                    action: 'yfj_create_page',
                    module_key: moduleKey,
                    module_name: moduleName,
                    nonce: nonce
                }, function(response) {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    if(response.success) {
                        var actionDiv = document.getElementById('yfj_actions_' + moduleKey);
                        actionDiv.style.display = 'block';
                        var prefixTxt = response.data.is_existing ? '<span style="color:#d63638;">(页面已存在)</span> ' : '<span style="color:#16a34a;">(创建成功)</span> ';
                        actionDiv.innerHTML = prefixTxt + '<a href="' + response.data.view_url + '" target="_blank" style="font-weight:bold;">👀 查看</a> | <a href="' + response.data.edit_url + '" target="_blank">✏️ 编辑</a>';
                        btn.style.display = 'none';
                    } else { alert('生成失败: ' + response.data); }
                });
            }
        </script>
        <?php
    }

    public function ajax_create_page() {
        check_ajax_referer('yfj_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) { wp_send_json_error('权限不足'); }

        $module_key  = sanitize_text_field($_POST['module_key']);
        $module_name = sanitize_text_field($_POST['module_name']);

        $shortcode   = "[yfj_{$module_key}]";

        // 直接使用中文作为页面标题，方便用户放到菜单里
        $page_title  = $module_name;

        //$existing_page = get_page_by_title($page_title, OBJECT, 'page');
        // 使用 WP_Query 替代已废弃的 get_page_by_title ---
        $query = new WP_Query([
            'post_type'              => 'page',
            'title'                  => $page_title,
            'post_status'            => 'any', // 查找任何状态的同名页面（包含草稿等）
            'posts_per_page'         => 1,
            'no_found_rows'          => true,  // 禁用 SQL CALC_FOUND_ROWS 以提升性能
            'ignore_sticky_posts'    => true,
            'update_post_term_cache' => false, // 禁用缓存更新以提升性能
            'update_post_meta_cache' => false,
        ]);
        $existing_page = !empty($query->posts) ? $query->posts[0] : null;

        if ($existing_page) {
            wp_send_json_success(['is_existing' => true, 'edit_url' => get_edit_post_link($existing_page->ID, 'raw'), 'view_url' => get_permalink($existing_page->ID)]);
        }

        // 页面内容只放短代码，绝对纯净
        $page_content = $shortcode;

        $new_page_id = wp_insert_post([
            'post_title'   => $page_title,
            'post_content' => $page_content,
            'post_status'  => 'publish',
            'post_type'    => 'page'
        ]);

        if (is_wp_error($new_page_id)) { wp_send_json_error('创建失败：' . $new_page_id->get_error_message()); }
        wp_send_json_success(['is_existing' => false, 'edit_url' => get_edit_post_link($new_page_id, 'raw'), 'view_url' => get_permalink($new_page_id)]);
    }

    public function add_plugin_settings_link($links) {
        $settings_link = '<a href="admin.php?page=yuanfenju-platform">设置</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}
new Yuanfenju_Astrology_Toolkit();