<?php
// 安全拦截：防止无数据报错
if (empty($data) || !is_array($data)) {
    echo '<div style="color:red; text-align:center; padding: 20px;">' . $this->t('暂无数据') . '</div>';
    return;
}
?>

<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<style>
    .yfj-result-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #334155; }
    .yfj-panel { background: #fff; border: 1px solid var(--yfj-border, #e2e8f0); border-radius: 8px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; }
    .yfj-panel-heading { background: #f8fafc; padding: 14px 20px; border-bottom: 1px solid var(--yfj-border, #e2e8f0); font-weight: 600; font-size: 16px; color: var(--yfj-text-dark, #0f172a); display: flex; align-items: center; gap: 8px; }

    /* 针对乙方卡片的区分配色 (可以稍微用偏紫/蓝色调区分) */
    .yfj-panel-partner { border-color: #c7d2fe; }
    .yfj-panel-heading-partner { background: #e0e7ff; border-bottom: 1px solid #c7d2fe; color: #4338ca; }

    .yfj-panel-body { padding: 20px; font-size: 14.5px; line-height: 1.8; color: #475569; }

    .yfj-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 14px; line-height: 1.6; }
    .yfj-info-grid strong { color: #1e293b; }

    .yfj-badge-red { color: #dc2626; font-weight: 600; }
    .yfj-badge-blue { color: #2563eb; font-weight: 600; }
    .yfj-highlight { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #0f172a; font-weight: 500; }

    .yfj-bazi-table { width: 100%; border-collapse: collapse; text-align: center; font-size: 14px; margin-top: 15px; }
    .yfj-bazi-table th, .yfj-bazi-table td { border: 1px solid #e2e8f0; padding: 8px; }
    .yfj-bazi-table-partner th, .yfj-bazi-table-partner td { border: 1px solid #c7d2fe; }
</style>

<div class="yfj-result-wrapper">

    <!-- 1. 甲方命盘 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('甲方命盘'); ?>
        </div>
        <div class="yfj-panel-body">
            <div class="yfj-info-grid">
                <div><strong><?php echo $this->t('甲方姓名：'); ?></strong> <?php echo esc_html($data['male']['name']); ?></div>
                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($data['male']['gongli']); ?></div>

                <div><strong><?php echo $this->t('生肖：'); ?></strong> <?php echo esc_html($data['male_sx']); ?></div>
                <div><strong><?php echo $this->t('命宫：'); ?></strong> <?php echo esc_html($data['minggong']['male_minggong']); ?></div>
                <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                    <strong><?php echo $this->t('您属于：'); ?></strong> <span class="yfj-highlight"><?php echo esc_html($data['minggong']['male_fengshui']); ?></span>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="yfj-bazi-table">
                    <tr style="color: #2563eb; background: #f1f5f9;">
                        <th>#</th>
                        <?php foreach ((array)$data['male']['tg_cg_god'] as $vo): ?>
                            <th><?php echo esc_html($vo); ?></th>
                        <?php endforeach; ?>
                        <th>#</th>
                    </tr>
                    <tr style="color: #dc2626;">
                        <th><?php echo esc_html($data['male']['sex']); ?></th>
                        <?php foreach ((array)$data['male']['bazi'] as $vo): ?>
                            <td style="font-weight: bold; font-size: 15px;"><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>(<?php echo esc_html($data['male']['kw']); ?>空)</td>
                    </tr>
                    <tr>
                        <th><?php echo $this->t('藏干'); ?></th>
                        <?php foreach ((array)$data['male']['dz_cg'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                    <tr>
                        <th></th>
                        <?php foreach ((array)$data['male']['dz_cg_god'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                    <tr>
                        <th><?php echo $this->t('衰旺'); ?></th>
                        <?php foreach ((array)$data['male']['day_cs'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                    <tr style="color: #16a34a;">
                        <th><?php echo $this->t('纳音'); ?></th>
                        <?php foreach ((array)$data['male']['na_yin'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. 乙方命盘 -->
    <div class="yfj-panel yfj-panel-partner">
        <div class="yfj-panel-heading yfj-panel-heading-partner">
            <span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('乙方命盘'); ?>
        </div>
        <div class="yfj-panel-body">
            <div class="yfj-info-grid">
                <div><strong><?php echo $this->t('乙方姓名：'); ?></strong> <?php echo esc_html($data['female']['name']); ?></div>
                <div style="grid-column: 1 / -1;"><strong><?php echo $this->t('出生公历：'); ?></strong> <?php echo esc_html($data['female']['gongli']); ?></div>

                <div><strong><?php echo $this->t('生肖：'); ?></strong> <?php echo esc_html($data['female_sx']); ?></div>
                <div><strong><?php echo $this->t('命宫：'); ?></strong> <?php echo esc_html($data['minggong']['female_minggong']); ?></div>
                <div style="grid-column: 1 / -1; margin-top: 5px; padding-top: 10px; border-top: 1px dashed #c7d2fe;">
                    <strong><?php echo $this->t('您属于：'); ?></strong> <span class="yfj-highlight" style="background:#e0e7ff; color:#4338ca;"><?php echo esc_html($data['minggong']['female_fengshui']); ?></span>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="yfj-bazi-table yfj-bazi-table-partner">
                    <tr style="color: #2563eb; background: #e0e7ff;">
                        <th>#</th>
                        <?php foreach ((array)$data['female']['tg_cg_god'] as $vo): ?>
                            <th><?php echo esc_html($vo); ?></th>
                        <?php endforeach; ?>
                        <th>#</th>
                    </tr>
                    <tr style="color: #dc2626;">
                        <th><?php echo esc_html($data['female']['sex']); ?></th>
                        <?php foreach ((array)$data['female']['bazi'] as $vo): ?>
                            <td style="font-weight: bold; font-size: 15px;"><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>(<?php echo esc_html($data['female']['kw']); ?>空)</td>
                    </tr>
                    <tr>
                        <th><?php echo $this->t('藏干'); ?></th>
                        <?php foreach ((array)$data['female']['dz_cg'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                    <tr>
                        <th></th>
                        <?php foreach ((array)$data['female']['dz_cg_god'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                    <tr>
                        <th><?php echo $this->t('衰旺'); ?></th>
                        <?php foreach ((array)$data['female']['day_cs'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                    <tr style="color: #16a34a;">
                        <th><?php echo $this->t('纳音'); ?></th>
                        <?php foreach ((array)$data['female']['na_yin'] as $vo): ?>
                            <td><?php echo esc_html($vo); ?></td>
                        <?php endforeach; ?>
                        <td>#</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. 合盘详细得分 -->
    <div class="yfj-panel">
        <div class="yfj-panel-heading">
            <span class="dashicons dashicons-clipboard"></span> <?php echo $this->t('合盘详细得分'); ?>
        </div>
        <div class="yfj-panel-body">
            <?php
            // 规范多语言：直接在定义时就包裹翻译方法，方便词条扫描工具抓取
            $fields = [
                'minggong'      => $this->t('合作合盘'),
                'nianqitongzhi' => $this->t('生意合盘'),
                'yueling'       => $this->t('财运合盘'),
                'rigan'         => $this->t('恋爱合盘'),
                'tiangan'       => $this->t('运势合盘'),
                'jiankang'      => $this->t('健康合盘')
            ];
            foreach ($fields as $key => $label):
                if(isset($data[$key])):
                    ?>
                    <div style="border-bottom: 1px dashed #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: #1e293b; font-size: 15px;"><?php echo esc_html($label); ?></strong>
                            <span class="yfj-badge-red" style="font-size: 15px;"><?php echo esc_html($data[$key]['score']); ?> <?php echo $this->t('分'); ?></span>
                        </div>
                        <p style="margin: 0 0 6px 0;"><strong><?php echo $this->t('说明：'); ?></strong> <span class="yfj-highlight"><?php echo esc_html($data[$key]['description']); ?></span></p>
                        <p style="margin: 0;"><strong><?php echo $this->t('详批：'); ?></strong> <?php echo esc_html($data[$key]['detail_description']); ?></p>
                    </div>
                    <?php
                endif;
            endforeach;
            ?>

            <div style="text-align: center; margin-top: 25px; background: #fef2f2; padding: 20px; border-radius: 6px; border: 1px solid #fecaca;">
                <h3 style="margin: 0; color: #b91c1c; font-size: 18px;">
                    <strong><?php echo $this->t('合盘总分：'); ?></strong>
                    <span style="font-size: 32px; font-weight: 800;"><?php echo esc_html($data['all_score']); ?></span>
                    <?php echo $this->t('分'); ?>
                </h3>
            </div>
        </div>
    </div>

    <!-- 测算告诫，免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回按钮 -->
    <div style="text-align: center; margin-top: 10px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                style="background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <?php echo $this->t('返回重测'); ?>
        </button>
    </div>

</div>