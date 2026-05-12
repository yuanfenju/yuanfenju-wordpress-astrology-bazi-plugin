<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全提取所有数据结构
$base_info   = $data['base_info'] ?? [];
$natal_info  = $base_info['natal_info'] ?? [];
$detail_info = $data['detail_info'] ?? [];
$chart_data  = $detail_info['chart_data'] ?? [];
$chart_desc  = $detail_info['chart_description'] ?? [];

// ==========================================
// 🚀 核心修复：SVG 路径层级与 XML 声明剔除
// ==========================================
$raw_svg = $detail_info['chart_svg'] ?? $detail_info['svg'] ?? '';
// 强制剔除 XML 声明，防止网页解析错误导致图片隐藏
$clean_svg = preg_replace('/<\?xml.*?\?>/i', '', $raw_svg);

$name = !empty($params['name']) ? esc_html($params['name']) : $this->t('求测者');
$sex  = isset($natal_info['gender']) ? ($natal_info['gender'] == 'female' ? $this->t('女') : $this->t('男')) : $this->t('未知');

// 🌐 动态语言后缀拾取
$lang_opt = get_option('yfj_language', 'zh-cn');
$sf = 'chinese';
$sf_short = 'zh';

if ($lang_opt === 'zh-tw') {
    $sf = 'chinese_traditional';
    $sf_short = 'zht';
} elseif ($lang_opt === 'en-us') {
    $sf = 'english';
    $sf_short = 'en';
}
?>

<style>
    .yfj-result-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid var(--yfj-border, #e2e8f0); border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid var(--yfj-border, #e2e8f0); font-weight: 600; font-size: 16px; color: var(--yfj-text-dark, #0f172a); display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; }

    .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 14px; line-height: 1.6; }
    .yfj-info-grid strong { color: #1e293b; }

    .yfj-table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .yfj-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; white-space: nowrap; }
    .yfj-table th, .yfj-table td { border: 1px solid #e2e8f0; padding: 10px 12px; }
    .yfj-table th { background: #f1f5f9; color: #475569; font-weight: 600; }

    .yfj-svg-container svg { width: 100%; max-width: 600px; height: auto; display: block; margin: 0 auto; }
    .yfj-text-highlight { color: #ff4c00; font-weight: 500; }
    .yfj-desc-block { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #e2e8f0; font-size: 14.5px; line-height: 1.8; color: #475569; }
    .yfj-desc-block:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .yfj-badge-pattern { background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; margin-right: 5px; border: 1px solid #fca5a5; }
</style>

<div class="yfj-result-wrapper">

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('基本信息'); ?>
        </div>
        <div class="yfj-panel-body yfj-info-grid">
            <div><strong><?php echo $this->t('姓名：'); ?></strong> <?php echo esc_html($name); ?> (<?php echo esc_html($sex); ?>)</div>
            <div><strong><?php echo $this->t('生命灵数：'); ?></strong> <span style="color:#dc2626; font-weight:bold; font-size:16px;"><?php echo esc_html($natal_info['numerology'] ?? '-'); ?></span></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($natal_info['birthday'] ?? '-'); ?></div>

            <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 10px; border-top: 1px dashed #e2e8f0; display: flex; gap: 20px; flex-wrap: wrap;">
                <span><strong><?php echo $this->t('经度：'); ?></strong> <?php echo esc_html($natal_info['longitude'] ?? '-'); ?></span>
                <span><strong><?php echo $this->t('纬度：'); ?></strong> <?php echo esc_html($natal_info['latitude'] ?? '-'); ?></span>
                <span><strong><?php echo $this->t('时区：'); ?></strong> <?php echo esc_html($natal_info['timezone'] ?? '-'); ?></span>
            </div>
        </div>
    </div>

    <?php if(!empty($clean_svg)): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-art"></span> <?php echo $this->t('本命星盘'); ?>
            </div>
            <div class="yfj-panel-body yfj-svg-container">
                <?php echo $clean_svg; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-star-filled"></span> <?php echo $this->t('星体落入星座'); ?>
        </div>
        <div class="yfj-panel-body yfj-table-responsive" style="padding: 0;">
            <table class="yfj-table">
                <thead>
                <tr>
                    <th><?php echo $this->t('星体'); ?></th>
                    <th><?php echo $this->t('落入星座'); ?></th>
                    <th><?php echo $this->t('落入宫位'); ?></th>
                    <th style="text-align: center;"><?php echo $this->t('状态'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($chart_data['planetData'] ?? [] as $vo): ?>
                    <tr>
                        <td class="yfj-text-highlight"><?php echo esc_html($vo["planet_{$sf}"] ?? $vo['planet_chinese'] ?? '-'); ?></td>
                        <td>
                            <span class="yfj-text-highlight"><?php echo esc_html($vo['sign']["sign_{$sf}"] ?? $vo['sign']['sign_chinese'] ?? ''); ?></span>
                            (<?php echo esc_html($vo['sign']['deg'] ?? '0'); ?>°<?php echo esc_html($vo['sign']['min'] ?? '0'); ?>′<?php echo esc_html($vo['sign']['sec'] ?? '0'); ?>″)
                        </td>
                        <td>
                            <span class="yfj-text-highlight"><?php echo esc_html($vo['house_id'] ?? '-'); ?><?php echo $this->t('宫'); ?></span>
                            (<?php echo esc_html($vo['house_deg'] ?? '0'); ?>°<?php echo esc_html($vo['house_min'] ?? '0'); ?>′<?php echo esc_html($vo['house_sec'] ?? '0'); ?>″)
                        </td>
                        <td style="text-align: center;">
                            <?php echo (!empty($vo['is_retrograde'])) ? '<strong style="color:#dc2626;">逆行 (R)</strong>' : '<span style="color:#64748b;">-</span>'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-admin-home"></span> <?php echo $this->t('各宫位置'); ?>
        </div>
        <div class="yfj-panel-body yfj-table-responsive" style="padding: 0;">
            <table class="yfj-table">
                <thead>
                <tr>
                    <th><?php echo $this->t('宫位'); ?></th>
                    <th><?php echo $this->t('宫头星座'); ?></th>
                    <th><?php echo $this->t('守护星'); ?></th>
                    <th><?php echo $this->t('飞星状态'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($chart_data['housesData'] ?? [] as $vo): ?>
                    <tr>
                        <td class="yfj-text-highlight"><?php echo esc_html($vo['house_id'] ?? '-'); ?><?php echo $this->t('宫'); ?></td>
                        <td>
                            <span class="yfj-text-highlight"><?php echo esc_html($vo['sign']["sign_{$sf}"] ?? $vo['sign']['sign_chinese'] ?? ''); ?></span>
                            <?php echo esc_html($vo['sign']['deg'] ?? '0'); ?>°<?php echo esc_html($vo['sign']['min'] ?? '0'); ?>′<?php echo esc_html($vo['sign']['sec'] ?? '0'); ?>″
                        </td>
                        <td><?php echo esc_html($vo['main_planet'][0]["planet_{$sf}"] ?? $vo['main_planet'][0]['planet_chinese'] ?? '-'); ?></td>
                        <td>
                            <?php if(!empty($vo['ruler_fly_into'][0])): ?>
                                <?php echo esc_html($vo['ruler_fly_into'][0]["fall_sign_{$sf}"] ?? $vo['ruler_fly_into'][0]['fall_sign_chinese'] ?? ''); ?>
                                <span class="yfj-text-highlight"><?php echo esc_html($vo['ruler_fly_into'][0]['fall_house_id'] ?? '-'); ?><?php echo $this->t('宫'); ?></span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-networking"></span> <?php echo $this->t('交角相位'); ?>
        </div>
        <div class="yfj-panel-body yfj-table-responsive" style="padding: 0;">
            <table class="yfj-table">
                <thead>
                <tr>
                    <th><?php echo $this->t('星体 1'); ?></th>
                    <th><?php echo $this->t('相位角度'); ?></th>
                    <th><?php echo $this->t('星体 2'); ?></th>
                    <th><?php echo $this->t('动能方向'); ?></th>
                    <th><?php echo $this->t('容许度误差'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($chart_data['planetData'] ?? [] as $planet):
                    foreach($planet['planet_allow_degree'] ?? [] as $aspect):
                        $direction = '—';
                        if (isset($aspect['in_out'])) {
                            if ((string)$aspect['in_out'] === '1') $direction = $this->t('入相');
                            elseif ((string)$aspect['in_out'] === '-1') $direction = $this->t('出相');
                        }
                        ?>
                        <tr>
                            <td style="color: #0ea5e9; font-weight: 500;"><?php echo esc_html($planet["planet_{$sf}"] ?? $planet['planet_chinese'] ?? '-'); ?></td>
                            <td style="color: #0ea5e9; font-weight: bold;">
                                <?php echo esc_html($aspect["aspect_{$sf}"] ?? $aspect['aspect_chinese'] ?? $aspect['allow'].'°'); ?>
                            </td>
                            <td style="color: #0ea5e9; font-weight: 500;"><?php echo esc_html($aspect["planet_{$sf}"] ?? $aspect['planet_chinese'] ?? '-'); ?></td>
                            <td style="color: #0ea5e9;"><?php echo esc_html($direction); ?></td>
                            <td style="color: #0ea5e9; font-size:13px;">
                                <?php echo esc_html($aspect['deg'] ?? '0'); ?>°<?php echo esc_html($aspect['min'] ?? '0'); ?>′<?php echo esc_html($aspect['sec'] ?? '0'); ?>″
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if(!empty($chart_desc['summary'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('生命蓝图总览'); ?>
            </div>
            <div class="yfj-panel-body">
                <?php foreach($chart_desc['summary'] as $vo): ?>
                    <div class="yfj-desc-block">
                        <strong>【<span class="yfj-text-highlight"><?php echo esc_html($vo['target'] ?? ''); ?></span>】：</strong>
                        <?php echo nl2br(esc_html($vo['description'] ?? '')); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($chart_desc['houses'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('生活领域背景 (宫位与星座)'); ?>
            </div>
            <div class="yfj-panel-body">
                <?php foreach($chart_desc['houses'] as $vo): ?>
                    <div class="yfj-desc-block">
                        <strong>【<span class="yfj-text-highlight"><?php echo esc_html($vo['target'] ?? ''); ?></span>】：</strong>
                        <?php echo nl2br(esc_html($vo['description'] ?? '')); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($chart_desc['planets']['sign'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('性格特质分析 (行星落座)'); ?>
            </div>
            <div class="yfj-panel-body">
                <?php foreach($chart_desc['planets']['sign'] as $vo): ?>
                    <div class="yfj-desc-block">
                        <strong>【<span class="yfj-text-highlight"><?php echo esc_html($vo['target'] ?? ''); ?></span>】：</strong>
                        <?php echo nl2br(esc_html($vo['description'] ?? '')); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($chart_desc['planets']['house'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('能量发挥领域 (行星落宫)'); ?>
            </div>
            <div class="yfj-panel-body">
                <?php foreach($chart_desc['planets']['house'] as $vo): ?>
                    <div class="yfj-desc-block">
                        <strong>【<span class="yfj-text-highlight"><?php echo esc_html($vo['target'] ?? ''); ?></span>】：</strong>
                        <?php echo nl2br(esc_html($vo['description'] ?? '')); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($chart_desc['angles'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('人生四大基石 (四轴解析)'); ?>
            </div>
            <div class="yfj-panel-body">
                <?php foreach($chart_desc['angles'] as $vo): ?>
                    <div class="yfj-desc-block">
                        <strong>【<span class="yfj-text-highlight"><?php echo esc_html($vo['target'] ?? ''); ?></span>】：</strong>
                        <?php echo nl2br(esc_html($vo['description'] ?? '')); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($chart_desc['aspects'])): ?>
        <div class="yfj-panel">
            <div class="yfj-panel-heading">
                <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('内在天赋与挑战 (相位解析)'); ?>
            </div>
            <div class="yfj-panel-body">
                <?php foreach($chart_desc['aspects'] as $vo): ?>
                    <div class="yfj-desc-block">
                        <strong>【<span class="yfj-text-highlight"><?php echo esc_html($vo['target'] ?? ''); ?></span>】：</strong>
                        <?php echo nl2br(esc_html($vo['description'] ?? '')); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php echo $this->get_disclaimer_html(); ?>

    <div style="text-align: center; margin-top: 30px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重排'); ?>
        </button>
    </div>
</div>