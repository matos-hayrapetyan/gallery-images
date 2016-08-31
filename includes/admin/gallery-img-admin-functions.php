<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Prints Admin Portfolios list pagination HTML
 *
 * @param $count_items
 * @param $page_number
 * @param string $serch_fields
 */
function gallery_img_print_html_nav($count_items, $page_number, $serch_fields = "")
{ ?>

    <script type="text/javascript">
        function clear_serch_texts() {
            document.getElementById("serch_or_not").value = '';
        }
        function submit_href(x, y) {
            var items_county =<?php if ($count_items) {
                if ($count_items % 20) {
                    echo ($count_items - $count_items % 20) / 20 + 1;
                } else echo ($count_items - $count_items % 20) / 20;
            } else echo 1;?>;
            if (document.getElementById("serch_or_not").value != "search") {
                clear_serch_texts();
            }
            switch (y) {
                case 1:
                    if (x >= items_county) document.getElementById('page_number').value = items_county;

                    else
                        document.getElementById('page_number').value = x + 1
                    break;
                case 2:
                    document.getElementById('page_number').value = items_county;
                    break;
                case -1:
                    if (x == 1) document.getElementById('page_number').value = 1;

                    else
                        document.getElementById('page_number').value = x - 1;
                    break;
                case -2:
                    document.getElementById('page_number').value = 1;
                    break;
                default:
                    document.getElementById('page_number').value = 1;
            }
            document.getElementById('admin_form').submit();

        }

    </script>
    <div class="tablenav top" style="width:95%">
        <?php if ($serch_fields != "") {
            echo $serch_fields;
        }
        ?>
        <div class="tablenav-pages">
            <span class="displaying-num"><?php echo $count_items; ?> items</span>
            <?php if ($count_items > 20) {

            if ($page_number == 1) {
                $first_page = "first-page disabled";
                $prev_page = "prev-page disabled";
                $next_page = "next-page";
                $last_page = "last-page";
            }
            if ($page_number >= (1 + ($count_items - $count_items % 20) / 20)) {
                $first_page = "first-page ";
                $prev_page = "prev-page";
                $next_page = "next-page disabled";
                $last_page = "last-page disabled";
            }

            ?>
            <span class="pagination-links">
	<a class="<?php echo $first_page; ?>" title="Go to the first page"
       href="javascript:submit_href(<?php echo $page_number; ?>,-2);">«</a>
<a class="<?php echo $prev_page; ?>" title="Go to the previous page"
   href="javascript:submit_href(<?php echo $page_number; ?>,-1);">‹</a>
<span class="paging-input">
	<span class="total-pages"><?php echo $page_number; ?></span>
	of <span class="total-pages">
	<?php echo ($count_items - $count_items % 20) / 20 + 1; ?>
	</span>
	</span>
	<a class="<?php echo $next_page ?>" title="Go to the next page"
       href="javascript:submit_href(<?php echo $page_number; ?>,1);">›</a>
<a class="<?php echo $last_page ?>" title="Go to the last page"
   href="javascript:submit_href(<?php echo $page_number; ?>,2);">»</a>
                <?php }
                ?>
</span>
        </div>
    </div>
    <input type="hidden" id="page_number" name="page_number" value="<?php if (isset($_POST['page_number'])) {
        echo $_POST['page_number'];
    } else {
        echo '1';
    } ?>"/>

    <input type="hidden" id="serch_or_not" name="serch_or_not" value="<?php if (isset($_POST["serch_or_not"])) {
        echo $_POST["serch_or_not"];
    } ?>"/>
    <?php
}

/**
 * Get Prtfolio id
 *
 * @return int
 */
function gallery_img_get_gallery_id()
{
    if (isset($_GET['page']) && $_GET['page'] == 'galleries_huge_it_gallery') {
        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
        } else {
            $id = 0;
        }
    }

    return $id;
}

/**
 * Get $_GET['task']
 *
 * @return string
 */
function gallery_img_get_gallery_task()
{
    if (isset($_GET['page']) && $_GET['page'] == 'galleries_huge_it_gallery') {
        if (isset($_GET["task"])) {
            $task = sanitize_text_field($_GET["task"]);
        } else {
            $task = '';
        }
    }


    return $task;
}

/**
 * @param $catt
 * @param string $tree_problem
 * @param int $hihiih
 *
 * @return array
 */
function gallery_img_open_cat_in_tree($catt, $tree_problem = '', $hihiih = 1)
{

    global $wpdb;
    global $glob_ordering_in_cat;
    static $trr_cat = array();
    if (!isset($search_tag))
        $search_tag = '';
    if ($hihiih)
        $trr_cat = array();
    foreach ($catt as $local_cat) {
        $local_cat->name = $tree_problem . $local_cat->name;
        array_push($trr_cat, $local_cat);
    }
    return $trr_cat;
}