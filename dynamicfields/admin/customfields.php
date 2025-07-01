<?php
include_once __DIR__ . '/../../../include/cp_header.php';

global $xoopsDB, $xoopsModule;

// İşlem türüne göre yönlendirme
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

switch ($op) {
    case 'delete':
        $field_id = isset($_REQUEST['field_id']) ? (int)$_REQUEST['field_id'] : 0;
        if ($field_id == 0) {
            redirect_header('customfields.php', 2, 'Geçersiz alan ID’si!');
        }

        if (isset($_POST['surdel']) && $_POST['surdel'] == true) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('customfields.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            $sql = "DELETE FROM " . $xoopsDB->prefix('dynamicfields_fields') . " WHERE field_id = $field_id";
            if ($xoopsDB->queryF($sql)) {
                redirect_header('customfields.php', 2, 'Özel alan başarıyla silindi!');
            } else {
                redirect_header('customfields.php', 2, 'Hata oluştu: ' . $xoopsDB->error());
            }
        } else {
            $sql = "SELECT field_label FROM " . $xoopsDB->prefix('dynamicfields_fields') . " WHERE field_id = $field_id";
            $result = $xoopsDB->query($sql);
            $row = $xoopsDB->fetchArray($result);
            xoops_confirm(['surdel' => true, 'field_id' => $field_id, 'op' => 'delete'], 'customfields.php', 
                          sprintf('"%s" adlı özel alanı silmek istediğinizden emin misiniz?', htmlspecialchars($row['field_label'])));
        }
        break;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $module_dirname = $xoopsDB->escape($_POST['module_dirname']);
            $field_name     = $xoopsDB->escape($_POST['field_name']);
            $field_label    = $xoopsDB->escape($_POST['field_label']);
            $field_type     = $xoopsDB->escape($_POST['field_type']);
            $field_required = isset($_POST['field_required']) ? 1 : 0;

            $sql = sprintf(
                "INSERT INTO %s (module_dirname, field_name, field_label, field_type, field_required)
                 VALUES ('%s', '%s', '%s', '%s', %d)",
                $xoopsDB->prefix('dynamicfields_fields'),
                $module_dirname, $field_name, $field_label, $field_type, $field_required
            );

            if ($xoopsDB->queryF($sql)) {
                redirect_header('customfields.php', 2, 'Özel alan başarıyla eklendi!');
            } else {
                redirect_header('customfields.php', 2, 'Hata oluştu: ' . $xoopsDB->error());
            }
        }
        // Formu göster (list ile aynı formu kullanıyoruz, bu yüzden list’e yönlendir)
        redirect_header('customfields.php', 0);
        break;

    case 'list':
    default:
        xoops_cp_header();

        // Modül listesi
        $moduleHandler = xoops_getHandler('module');
        $criteria = new CriteriaCompo(new Criteria('isactive', 1));
        $modules = $moduleHandler->getObjects($criteria);

        // Yeni özel alan ekleme formu
        echo '<h2>Yeni Özel Alan Ekle</h2>';
        echo '<form method="post" action="customfields.php?op=add">';
        echo '<table class="outer" width="100%" cellspacing="1">';
        echo '<tr><td class="head" width="20%">Modül Seç</td><td class="even">';
        echo '<select name="module_dirname">';
        foreach ($modules as $module) {
            echo '<option value="' . htmlspecialchars($module->getVar('dirname')) . '">' . htmlspecialchars($module->getVar('name')) . '</option>';
        }
        echo '</select></td></tr>';
        echo '<tr><td class="head">Alan Adı</td><td class="even"><input type="text" name="field_name" required></td></tr>';
        echo '<tr><td class="head">Alan Etiketi</td><td class="even"><input type="text" name="field_label" required></td></tr>';
        echo '<tr><td class="head">Alan Tipi</td><td class="even">
              <select name="field_type">
                <option value="text">Metin</option>
                <option value="textarea">Metin Alanı</option>
                <option value="select">Seçim Kutusu</option>
              </select></td></tr>';
        echo '<tr><td class="head">Zorunlu</td><td class="even"><input type="checkbox" name="field_required" value="1"></td></tr>';
        echo '<tr><td class="head"></td><td class="even"><input type="submit" name="submit" value="Kaydet"></td></tr>';
        echo '</table>';
        echo '</form><hr>';

        // Mevcut alanları listele
        echo '<h2>Mevcut Alanlar</h2>';
        $sql = "SELECT * FROM " . $xoopsDB->prefix('dynamicfields_fields') . " ORDER BY field_id DESC";
        $result = $xoopsDB->query($sql);

        echo '<table class="outer" width="100%" cellspacing="1">';
        echo '<tr class="head"><th>ID</th><th>Modül</th><th>Ad</th><th>Etiket</th><th>Tip</th><th>Zorunlu</th><th>İşlem</th></tr>';

        $even = false;
        while ($row = $xoopsDB->fetchArray($result)) {
            $class = $even ? 'even' : 'odd';
            echo '<tr class="' . $class . '">';
            echo '<td>' . $row['field_id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['module_dirname']) . '</td>';
            echo '<td>' . htmlspecialchars($row['field_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['field_label']) . '</td>';
            echo '<td>' . htmlspecialchars($row['field_type']) . '</td>';
            echo '<td>' . ($row['field_required'] ? 'Evet' : 'Hayır') . '</td>';
            echo '<td><a href="customfields.php?op=delete&field_id=' . $row['field_id'] . '" title="Sil">Sil</a></td>';
            echo '</tr>';
            $even = !$even;
        }
        echo '</table>';

        xoops_cp_footer();
        break;
}
?>