<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<?php
// 安全提取所有数据结构，防止 Sandbox 模式或 API 报错时抛出 Warning
$base   = $data['base_info'] ?? [];
$bazi   = $data['bazi_info'] ?? [];
$start  = $data['start_info'] ?? [];
$detail = $data['detail_info'] ?? [];
$dayun  = $data['dayun_info'] ?? [];

// 基础变量提取
$name    = $base['name'] ?? ($data['name'] ?? '未知');
$sex     = $base['sex'] ?? '未知';
$display_sex = str_replace(['乾造', '坤造'], ['男', '女'], $sex); // 把专业术语替换为男女
$nongli  = $base['nongli'] ?? '';
$gongli  = $base['gongli'] ?? '';
$qiyun   = $base['qiyun'] ?? '';
$jiaoyun = $base['jiaoyun'] ?? '';
$xz      = $start['xz'] ?? '';
$sx      = $start['sx'] ?? '';
$zhengge = $base['zhengge'] ?? '未知';
?>

<style>
    .yfj-result-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid var(--yfj-border, #e2e8f0); border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid var(--yfj-border, #e2e8f0); font-weight: 600; font-size: 16px; color: var(--yfj-text-dark, #0f172a); display: flex; align-items: center; gap: 8px; }
    .yfj-panel-body { padding: 20px; }

    .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 14px; line-height: 1.6; }
    .yfj-info-grid strong { color: #1e293b; }

    .yfj-table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .yfj-table { width: 100%; border-collapse: collapse; text-align: center; font-size: 14px; white-space: nowrap; }
    .yfj-table th, .yfj-table td { border: 1px solid #e2e8f0; padding: 10px 8px; }
    .yfj-table th { background: #f1f5f9; color: #475569; font-weight: normal; }

    .yfj-text-blue { color: #2563eb; font-weight: 500; }
    .yfj-text-red { color: #dc2626; font-weight: 600; }
    .yfj-text-green { color: #059669; }
</style>

<div class="yfj-result-wrapper">

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-id-alt"></span> <?php echo $this->t('排盘结果'); ?>
        </div>
        <div class="yfj-panel-body yfj-info-grid">
            <div><strong><?php echo $this->t('命主姓名：'); ?></strong> <?php echo esc_html($name); ?> &nbsp;|&nbsp; <strong><?php echo $this->t('性别：'); ?></strong> <?php echo esc_html($display_sex); ?></div>
            <div><strong><?php echo $this->t('星座：'); ?></strong> <?php echo esc_html($xz); ?> &nbsp;|&nbsp; <strong><?php echo $this->t('生肖：'); ?></strong> <?php echo esc_html($sx); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($gongli); ?></div>
            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生农历：'); ?></strong> <?php echo esc_html($nongli); ?></div>

            <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 5px; border-top: 1px dashed #eee;">
                <strong><?php echo $this->t('命理格局：'); ?></strong>
                <span style="color: #dc2626; font-weight: bold; font-size: 16px;">
                <?php echo esc_html($zhengge); ?>
                </span>
            </div>

            <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('起运日期：'); ?></strong> <?php echo esc_html($qiyun); ?> &nbsp;&nbsp;&nbsp; <strong><?php echo $this->t('交运日期：'); ?></strong> <?php echo esc_html($jiaoyun); ?></div>
        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-grid-view"></span> <?php echo $this->t('八字排盘'); ?>
        </div>
        <div class="yfj-panel-body yfj-table-responsive" style="padding: 0;">
            <table class="yfj-table">
                <tbody>
                <tr class="yfj-text-blue">
                    <th style="width: 80px;"><?php echo $this->t('主星'); ?></th>
                    <?php foreach($bazi['tg_cg_god'] ?? [] as $val): ?> <th><?php echo esc_html($val); ?></th> <?php endforeach; ?>
                    <th>#</th>
                </tr>
                <tr class="yfj-text-red" style="font-size: 18px;">
                    <td><?php echo esc_html($sex); ?></td>
                    <?php foreach($bazi['bazi'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                    <td style="font-size: 12px; font-weight: normal; color: #64748b;">(<?php echo esc_html($bazi['kw'] ?? ''); ?>空)</td>
                </tr>
                <tr>
                    <td><?php echo $this->t('藏干'); ?></td>
                    <?php foreach($bazi['dz_cg'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                    <td style="color: #94a3b8;">#</td>
                </tr>
                <tr>
                    <td><?php echo $this->t('副星'); ?></td>
                    <?php foreach($bazi['dz_cg_god'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                    <td style="color: #94a3b8;">#</td>
                </tr>
                <tr>
                    <td><?php echo $this->t('星运'); ?></td>
                    <?php foreach($bazi['day_cs'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                    <td style="color: #94a3b8;">#</td>
                </tr>
                <tr>
                    <td><?php echo $this->t('自坐'); ?></td>
                    <td><?php echo esc_html($detail['zizuo']['year'] ?? '-'); ?></td>
                    <td><?php echo esc_html($detail['zizuo']['month'] ?? '-'); ?></td>
                    <td><?php echo esc_html($detail['zizuo']['day'] ?? '-'); ?></td>
                    <td><?php echo esc_html($detail['zizuo']['hour'] ?? '-'); ?></td>
                    <td style="color: #94a3b8;">#</td>
                </tr>
                <tr>
                    <td><?php echo $this->t('空亡'); ?></td>
                    <td><?php echo esc_html($detail['kongwang']['year'] ?? '-'); ?></td>
                    <td><?php echo esc_html($detail['kongwang']['month'] ?? '-'); ?></td>
                    <td><?php echo esc_html($detail['kongwang']['day'] ?? '-'); ?></td>
                    <td><?php echo esc_html($detail['kongwang']['hour'] ?? '-'); ?></td>
                    <td style="color: #94a3b8;">#</td>
                </tr>
                <tr class="yfj-text-green">
                    <td><?php echo $this->t('纳音'); ?></td>
                    <?php foreach($bazi['na_yin'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                    <td style="color: #94a3b8;">#</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-shield"></span> <?php echo $this->t('四柱神煞'); ?>
        </div>
        <div class="yfj-panel-body" style="font-size: 14px; line-height: 1.8;">
            <?php $jishen = $start['jishen'] ?? []; ?>
            <p><strong><?php echo $this->t('年柱：'); ?></strong> <?php echo esc_html($jishen[0] ?? '-'); ?></p>
            <p><strong><?php echo $this->t('月柱：'); ?></strong> <?php echo esc_html($jishen[1] ?? '-'); ?></p>
            <p><strong><?php echo $this->t('日柱：'); ?></strong> <?php echo esc_html($jishen[2] ?? '-'); ?></p>
            <p><strong><?php echo $this->t('时柱：'); ?></strong> <?php echo esc_html($jishen[3] ?? '-'); ?></p>
        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-calendar-alt"></span> <?php echo $this->t('大运排盘'); ?>
        </div>
        <div class="yfj-panel-body yfj-table-responsive" style="padding: 0;">
            <table class="yfj-table">
                <tbody>
                <tr class="yfj-text-blue">
                    <th>#</th>
                    <?php foreach($dayun['big_god'] ?? [] as $val): ?> <th><?php echo esc_html($val); ?></th> <?php endforeach; ?>
                </tr>
                <tr class="yfj-text-red">
                    <td><?php echo $this->t('大运'); ?></td>
                    <?php foreach($dayun['big'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                </tr>
                <tr>
                    <td style="color: #94a3b8;">#</td>
                    <?php foreach($dayun['big_cs'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                </tr>
                <tr>
                    <td><?php echo $this->t('虚岁'); ?></td>
                    <?php foreach($dayun['xu_sui'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                </tr>
                <tr>
                    <td><?php echo $this->t('始于'); ?></td>
                    <?php foreach($dayun['big_start_year'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                </tr>

                <?php
                for($i=0; $i<=9; $i++):
                    $year_key = "years_info{$i}";
                    if(!empty($dayun[$year_key])):
                        ?>
                        <tr>
                            <td style="color: #94a3b8;">#</td>
                            <?php foreach($dayun[$year_key] as $yr): ?>
                                <td style="color: #64748b;"><?php echo esc_html($yr['year_char'] ?? ''); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php
                    endif;
                endfor;
                ?>

                <tr>
                    <td><?php echo $this->t('止于'); ?></td>
                    <?php foreach($dayun['big_end_year'] ?? [] as $val): ?> <td><?php echo esc_html($val); ?></td> <?php endforeach; ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-awards"></span> <?php echo $this->t('大运神煞'); ?>
        </div>
        <div class="yfj-panel-body" style="font-size: 14px; line-height: 1.8;">
            <?php foreach($detail['dayunshensha'] ?? [] as $shensha): ?>
                <p><strong><?php echo esc_html($shensha['tgdz'] ?? '-'); ?>：</strong> <?php echo esc_html($shensha['shensha'] ?? '-'); ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 测算告诫，免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <div style="text-align: center; margin-top: 30px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重排'); ?>
        </button>
    </div>
</div>