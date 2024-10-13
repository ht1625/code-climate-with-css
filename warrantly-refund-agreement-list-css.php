<html lang="tr">
<head>
    <?php $this->inc('components/master_header');
    use Illuminate\Helpers\Redirection; ?>
    <link href="<?= asset('vendor/datetimepicker-master/mmnt.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="<?= ass('css/eight-d-demo.css') ?>" rel="stylesheet">
    <title><?= lang('warrantly_refund', 'agreement_list_page_title') ?> | otoedi.v3</title>

    <style>
        body .select2-container {
            width: 100%!important;
        }

        body .select2-container .select2-selection {
            height: calc(1.5em + 0.75rem + 2px) !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: #495057 !important;
            background-color: #fff !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem !important;
        }

        body .select2-container .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px) !important;
        }

        body .select2-container--default .select2-selection--single .select2-selection__rendered {
            border-right: 1px solid #ced4da !important;
            padding-right: 2px !important;
            font-size: 13px !important;
        }

        body .select2-dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem !important;
        }

        body .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 13px !important;
        }

        body .select2-results__option {
            padding: 6px 12px !important;
            font-size: 13px !important;
            line-height: 1.5 !important;
        }

        body .select2-results__option--highlighted {
            background-color: #f7f7f7 !important;
            color: #333 !important;
        }

        body .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 0.75rem + 2px) !important;
            color: #495057 !important;
        }

        .inactive-link {
            pointer-events: none; 
            cursor: default; 
            text-decoration: none; 
            opacity: 0.6;
        }

        .stepper-wrapper {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 100%;
        }
        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        
            @media (max-width: 768px) {
            font-size: 12px;
            }
        }
        
        .stepper-item::before {
            position: absolute;
            content: "";
            border-bottom: 0.5px solid white;
            width: 100%;
            top: 20px;
            left: -50%;
            z-index: 2;
        }
        
        .stepper-item::after {
            position: absolute;
            content: "";
            border-bottom: 0.5px solid white;
            width: 100%;
            top: 20px;
            left: 50%;
            z-index: 2;
        }
        
        .stepper-item .step-counter {
            position: relative;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ccc;
            margin-bottom: 6px;
        }
        
        .stepper-item.active {
            font-weight: bold;
        }
        
        .stepper-item.completed .step-counter {
            background-color: #28a745;
            color:white !important;
        }
        
        .stepper-item.completed::after {
            position: absolute;
            content: "";
            border-bottom: 0.5px solid white;
            width: 100%;
            top: 20px;
            left: 50%;
            z-index: 3;
        }
        
        .stepper-item:first-child::before {
            content: none;
        }
        .stepper-item:last-child::after {
            content: none;
        }

        .paddingTopLangIcon{
            padding-top: 2%;
        }
        
    </style>

    <?php 

    // set download file
    $file_list_idx = array();
    foreach($file_list_info as $file){
        $file_list_idx[$file['files_id']] = $file;
        $file_list_idx[$file['files_id']]['data'] = generateDocument($agreement_relation_file[$file['files_id']], $file);
    }

    function generateDocument($agreement_id, $file){

        $pathSuitImage = __STORAGE__."/warrantly-refund/agreement/".$agreement_id."/".$file['files_id']."/".$file['filename'];
        $data = img8D($pathSuitImage);

        return $data;

    }

    $lang = lang('type', 'language_type');

    // Utility functions for rendering status icons and generating documents
    function getStatusIcon($status, $lang) {
        $title_status = $lang == "EN" ? "The Process Awaiting Approval" : "İşlem Onay Bekliyor";
        if ($status == "2") {
            $title_status = $lang == "EN" ? "The Process Approved" : "İşlem Onaylandı";
            return '<i class="mdi mdi-circle s-26 icon-hover" title="' . $title_status . '" style="color: green; filter: drop-shadow(0 0 5px green); font-size: 200%;"></i>';
        } elseif ($status == "1") {
            $title_status = $lang == "EN" ? "The Process Rejected" : "İşlem Reddedildi";
            return '<i class="mdi mdi-circle s-26 icon-hover" title="' . $title_status . '" style="color: red; filter: drop-shadow(0 0 5px red); font-size: 200%;"></i>';
        } else {
            return '<i class="mdi mdi-circle s-26 icon-hover" title="' . $title_status . '" style="color: #FFC107; filter: drop-shadow(0 0 5px #FFC107); font-size: 200%;"></i>';
        }
    }

    function getStatusText($status, $lang) {
        switch ($status) {
            case "0":
                return $lang === "TR" ? 'Devam Ediyor' : 'Ongoing';
            case "1":
                return $lang === "TR" ? 'Geçerli' : 'Valid';
            default:
                return $lang === "TR" ? 'Bitti' : 'Finished';
        }
    }
    
    // set  newPaginate value
    $value_new_page = [
        "sortDirection" => $sortDirection,
        "totalPage" => $totalPage,
        "sortField" => $sortField,
        "page" => $page,
    ]; 
    
    // Sütun adları
    $columns = [
        'Analiz Onay Durumu', 
        'Anlaşma Durumu', 
        'Anlaşma No', 
        'Anlaşma Oluşturma Tarihi',
        'Müşteri Bilgisi', 
        'İlgili PLantler', 
        'Ürün Grubu', 
        'Proje Adı', 
        'Analiz Sunumu', 
        'İlk Sorumluluk Oranı', 
        'Belirlenen Sorumluluk Oranı', 
        'Parça Adeti', 
        'Anlaşma Onay Tarihi', 
        'Anlaşma Kayıt Tarihi',
        'İşlemler'
    ];

    if($lang == "EN"){
        $columns = [
            'Analysis Approval Status', 
            'Deal Status', 
            'Deal No', 
            'Date of Treaty Creation',
            'Customer Information', 
            'Related PLants', 
            'Product Group', 
            'Project Name', 
            'Analysis Presentation',
            'First Liability Rate', 
            'Determined Responsibility Ratio', 
            'Number of Pieces',  
            'Agreement Approval Date', 
            'Agreement Record Date',
            'Transactions'
        ];
    }
    ?>

</head>
<body>
    <div class="page" data-root="warrantly_refund" data-page="warrantl-refund-agreement">
        <?php $this->inc('components/navbar'); ?>
        <div class="container-fluid">
            <div class="row px-2">
                <div class="offset-xl-2 col-xl-8 bg-light pb-4 px-26px">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="col-12 box-container px-4 py-3 ">

                                <div class="row">
                                    <div class="col-lg-8 col-sm-6 box-title">   
                                         <?= lang('warrantly_refund', 'warrantly_refund_aggreement_title') ?>
                                        <br>
                                        <a type="button" href="/warrantly/refund/agreement/view" style="margin-top: 5px"
                                                    class="btn-soft-success s-12 medium pt-2 pb-1" style="padding-left:2%;padding-right:2%;"> <i
                                                        class="mdi mdi-plus"></i> <?= lang('warrantly_refund', 'warrantly_refund_aggreement_create_btn') ?>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 text-right" style="margin-top: 15px">
                                        <form method="get" id="quick-search">
                                            <div class="input-group input-group-sm mb-2 mt-2">
                                                <input class="form-control form-control-sm"
                                                    placeholder="<?= lang('general', 'fast_search') ?>"
                                                    name="quick-search"
                                                    value="<?php if(isset($_GET['quick-search'])){ echo $_GET['quick-search']; } ?>" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="mdi mdi-file-find"></i>
                                                    </span>
                                                </div>
                                                <span onclick="resetQuickFilter();"
                                                    id="filter-reset" class="btn-soft-danger s-12 medium pt-2 ml-2"
                                                    <?= (isset($_GET['quick-search'])) ? '' : 'hidden' ?>>
                                                    <i class="mdi mdi-close"></i>
                                                </span>
                                                </span>
                                                <span data-toggle="modal" data-target="#filter-modal"
                                                    class="btn-soft-warning s-12 medium pt-2 ml-2">
                                                    <i class="mdi mdi-filter-outline"></i>
                                                    <?= lang('general', 'filter') ?>
                                                </span>
                                                <?php
                                                $column_notFilter = [
                                                    'sortField', 'sortDirection', 'offset', 'quick-search', 'page', 'totalPage'
                                                ];
                                                foreach($column_notFilter as $filter_idx){
                                                    if(in_array($filter_idx, $get)){
                                                        unset($get[$filter]);
                                                    }
                                                }
                                                $query_string = http_build_query($get);
                                                $url = "/warrantly/refund/agreement/excel/download/?" . $query_string;
                                                ?>
                                                <a type="button" href="<?=$url ?>" id="excelDownloadButton" class="btn-soft-warning s-12 medium pt-2 ml-2">
                                                    <i class="mdi mdi-download"></i> EXCEL
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <?php if(isset($message_text) && $message_text != null && isset($message_type) && $message_type != null){ ?>

                                <div class="alert alert-<?=$message_type ?> alert-dismissible fade show mt-4"
                                    role="alert">
                                    <label for="message_text"></label>
                                    <label for="message_type"></label>
                                    <strong><?=$message_type ?>!</strong> <?=$message_text ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <?php } ?>

                                <div class="container-fluid">
                                    <div class="row mt-3">

                                        <div class="col-12">

                                            <?php if(empty($agreement_list)){ ?>
                                            <div style="display: flex; justify-content: center; align-items: center; height: 50vh;">
                                                <div style="text-align: center; color: rgba(0, 0, 0, 0.5);">
                                                    <?= lang('warrantly_refund', 'no_record_table') ?>
                                                </div>
                                            </div>
                                            <?php }else{ ?>
                                            <!-- React bileşeninin render edileceği kök eleman -->
                                            <div id="react-root">
                                            <table class="table table-responsive" style="line-height: unset; max-width: 100%; height: 500px;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <?php foreach ($columns as $index => $item): ?>
                                                            <th style="font-size: 11px; font-weight: 500; position: sticky; top: 0; background-color: white;">
                                                                <?= $item; ?>
                                                            </th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($agreement_list as $index => $agreement_detail): ?>
                                                        <tr>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?= getStatusIcon($agreement_detail['analysis_confirmation_status'], $lang); ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?= getStatusText($agreement_detail['agreement_status'], $lang); ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?= $agreement_detail['agreement_number']; ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?= date('Y-m-d H:i', strtotime($agreement_detail['agreement_created_date'])); ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?= $agreement_detail['short_name']; ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?php
                                                                $plants = json_decode($agreement_detail['related_plants']);
                                                                foreach ($plants as $plant) {
                                                                    echo '<span class="btn-soft-warning s-12"><i class="mdi mdi-circle-medium"></i> ' . $location_plant_list[$plant] . '</span>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?= $lang === "TR" ? $agreement_detail['description'] : $agreement_detail['description_en']; ?>
                                                            </td>
                                                            <td class="text-center" style="font-size: 11px;">
                                                                <?= generateDocument($agreement_detail['agreement_id'], $file_list_idx[$agreement_detail['analyis_present_file_id']]); ?>
                                                            </td>
                                                            <td class="text-center" style="width: 1%; border-left: 1px solid #949494; <?= $is_plus === 1 ? 'border-right: 1px solid #949494;' : '' ?> border-bottom: <?= $is_plus === 0 ? '1px solid #ccc;' : '1px solid #fff;' ?> border-top: 0;">
                                                            </td>
                                                            <td class="text-center" style="<?= $hiddenColumnStyle; ?> font-size: 11px;">
                                                                <?= $agreement_detail['first_responsibility_rate']; ?> %
                                                            </td>
                                                            <td class="text-center" style="<?= $hiddenColumnStyle; ?> font-size: 11px;">
                                                                <?= $agreement_detail['determined_responsibility_rate']; ?> %
                                                            </td>
                                                            <td class="text-center" style="<?= $hiddenColumnStyle; ?> font-size: 11px;">
                                                                <?= $agreement_detail['piece_quantity']; ?>
                                                            </td>
                                                            <td class="text-center" style="<?= $hiddenColumnStyle; ?> font-size: 11px;">
                                                                <?= isset($agreement_detail['agreement_confirmation_date']) && strtotime($agreement_detail['agreement_confirmation_date']) 
                                                                    ? date('Y-m-d H:i', strtotime($agreement_detail['agreement_confirmation_date'])) 
                                                                    : '--'; ?>
                                                            </td>
                                                            <td class="text-center" style="<?= $hiddenColumnStyle; ?> font-size: 11px; <?= $is_plus === 0 ? 'border-right: 1px solid #949494;' : '' ?>">
                                                                <?= isset($agreement_detail['agreement_validity_date']) && strtotime($agreement_detail['agreement_validity_date']) 
                                                                    ? date('Y-m-d H:i', strtotime($agreement_detail['agreement_validity_date'])) 
                                                                    : '--'; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <span style="display: flex; align-items: center; justify-content: space-between;">
                                                                    <span class="icon-wrapper" style="background-color: #FFC107; width: 25px; height: 25px; line-height: 25px;">
                                                                        <a
                                                                            href="/warrantly/refund/agreement/edit/<?= $agreement_detail['agreement_id']; ?>"
                                                                            style="<?= $agreement_detail['analysis_confirmation_status'] !== "2" && ($is_master == 1 || ($user_id == $agreement_detail['fk_user_id']))
                                                                                ? '' 
                                                                                : 'opacity: 0.6; cursor: not-allowed;' ?>">
                                                                            <i class="mdi mdi-pencil s-26" style="font-size: 150%;"></i>
                                                                        </a>
                                                                    </span>
                                                                    <span class="icon-wrapper icon-show" style="width: 25px; height: 25px; line-height: 25px;">
                                                                        <a href="/warrantly/refund/agreement/detail/<?= $agreement_detail['agreement_id']; ?>">
                                                                            <i class="mdi mdi-eye s-26" style="font-size: 150%;"></i>
                                                                        </a>
                                                                    </span>
                                                                    <span class="icon-wrapper icon-add" style="width: 25px; height: 25px; line-height: 25px;">
                                                                        <a href="/warrantly/refund/agreement/zip/download/<?= $agreement_detail['agreement_id']; ?>">
                                                                            <i class="mdi mdi-download-box s-26" style="font-size: 150%;"></i>
                                                                        </a>
                                                                    </span>
                                                                    <span class="icon-wrapper icon-delete" style="width: 25px; height: 25px; line-height: 25px;">
                                                                        <a 
                                                                            href="<?= $is_master === 1 ? '#' : 'javascript:void(0);'; ?>" 
                                                                            onclick="<?= $is_master === 1 ? "return confirm('Bu işlemi yapmak istediğinize emin misiniz?');" : ''; ?>"
                                                                            style="<?= $is_master !== 1 ? 'opacity: 0.6; cursor: not-allowed;' : ''; ?>">
                                                                            <i class="mdi mdi-delete s-26" style="font-size: 150%;"></i>
                                                                        </a>
                                                                    </span>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            </div>
                                            <!-- Add paginate for react-agreement-list-->
                                            </div>
                                            <!-- 20231122 -->
                                            <?php $prePage = $page-1; $nextPage = $page+1; ?>
                                            <div class="col-md-12 pagination" align="center">

                                                <?php if ($page > 3): ?>                    
                                                    <a class="page-item page-link <?php if($page == $prePage){ echo "active"; } ?>" style="color:darkorange !important" onclick="newPaginate('<?php echo $sortField ?>', '<?php echo $sortDirection ?>', '<?php echo $totalPage ?>', '<?php echo $prePage ?>')"><span class='sortIcon fa fa-arrow-left'></span></a>
                                                    <a class="page-item page-link <?php if($page == 1){ echo "active"; } ?>" style="color:darkorange !important" onclick="newPaginate('<?php echo $sortField ?>', '<?php echo $sortDirection ?>', '<?php echo $totalPage ?>', '1')">1</a>
                                                <?php endif ?>

                                                <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPage); $i++): ?>
                                                    <?php if ($totalPage != 1): ?>
                                                        <?php if ($i == $page): ?>
                                                            <a class="page-item page-link <?php if($page == $i){ echo "active"; } ?>" style="color:darkorange !important" class='active'><?php echo $i ?></a>
                                                        <?php else: ?>
                                                            <a class="page-item page-link <?php if($page == $i){ echo "active"; } ?>" style="color:darkorange !important" onclick="newPaginate('<?php echo $sortField ?>', '<?php echo $sortDirection ?>', '<?php echo $totalPage ?>', '<?php echo $i ?>')"><?php echo $i ?></a>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                <?php endfor ?>

                                                <?php if ($page < $totalPage - 2): ?>                                      
                                                    <a class="page-item page-link <?php if($page == $totalPage){ echo "active"; } ?>" style="color:darkorange !important" onclick="newPaginate('<?php echo $sortField ?>', '<?php echo $sortDirection ?>', '<?php echo $totalPage ?>', '<?php echo $totalPage ?>')"><?php echo $totalPage ?></a>
                                                    <a class="page-item page-link <?php if($page == $nextPage){ echo "active"; } ?>" style="color:darkorange !important" onclick="newPaginate('<?php echo $sortField ?>', '<?php echo $sortDirection ?>', '<?php echo $totalPage ?>', '<?php echo $nextPage ?>')"><span class='sortIcon fa fa-arrow-right'></span></a>
                                                <?php endif ?>

                                            </div>
                                            <!-- 20231122 -->

                                        </div>
                                        <?php } ?>

                                        </div>
                                           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->inc('components/footer'); ?>
    </div>
    <!-- modal filtre -->
    <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filter-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filter-modal"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="mdi mdi-window-close"></i> </span>
                    </button>
                </div>
                <form method="get">
                    <div class="modal-body py-2">
                        <div class="container-fluid">
                            <h4 class="thin text-center"><i
                                    class="mdi mdi-filter-outline"></i><?= lang('general', 'filter') ?></h4>
                            <div class="row py-4">
                                                    
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="agreement_number"><?= lang('warrantly_refund', 'agreement_number') ?></label>
                                    <div class="input-group">
                                        <input name="agreement_number" id="agreement_number" class="form-control"
                                            value="<?php if(isset($_GET['agreement_number']) || isset($filter_value['agreement_number'])){ echo isset($_GET['agreement_number']) ? $_GET['agreement_number'] : $filter_value['agreement_number']; } ?>"
                                            placeholder="<?= lang('warrantly_refund', 'agreement_number_filter') ?>" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="customer_party_id"><?= lang('warrantly_refund', 'customer_party_id') ?></label>
                                    <div class="input-group">
                                        <select class="form-control" name="customer_party_id" id="customer_party_id" aria-label="Default select example">
                                            <option disabled selected="selected" value=""><?= lang('general', 'select') ?></option>
                                            <?php foreach($related_party_list as $party){ ?>                            
                                                <option value="<?= $party['party_id'] ?>"
                                                    <?php if(
                                                        (isset($_GET['customer_party_id']) && $_GET['customer_party_id'] == $party['party_id']) || 
                                                        (isset($filter_value['customer_party_id']) && $filter_value['customer_party_id'] == $party['party_id'])
                                                        ) { echo "selected"; } ?>>
                                                    <?php echo $party['short_name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="related_plants"><?= lang('warrantly_refund', 'related_plants') ?></label>
                                    <div class="input-group">
                                        <select class="form-control" name="related_plants" id="related_plants" aria-label="Default select example">
                                            <option disabled selected="selected" value=""><?= lang('general', 'select') ?></option>
                                            <?php foreach($location_plant_list as $party_id => $name){ ?>                            
                                                <option value="<?= $party_id ?>"
                                                    <?php if(
                                                        (isset($_GET['related_plants']) && $_GET['related_plants'] == $party_id) || 
                                                        (isset($filter_value['related_plants']) && $filter_value['related_plants'] == $party_id)
                                                        ) { echo "selected"; } ?>>
                                                    <?php echo $name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="project_name"><?= lang('warrantly_refund', 'project_name') ?></label>
                                    <div class="input-group">
                                        <input name="project_name" id="project_name" class="form-control"
                                            value="<?php if(isset($_GET['project_name']) || isset($filter_value['project_name'])){ echo isset($_GET['project_name']) ? $_GET['project_name'] : $filter_value['project_name']; } ?>"
                                            placeholder="<?= lang('warrantly_refund', 'project_name_filter') ?>" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="first_responsibility_rate"><?= lang('warrantly_refund', 'first_responsibility_rate') ?> (%)</label>
                                    <div class="input-group">
                                    <input name="first_responsibility_rate" id="first_responsibility_rate" class="form-control"
                                            value="<?php if(isset($_GET['first_responsibility_rate']) || isset($filter_value['first_responsibility_rate'])){ 
                                                    echo isset($_GET['first_responsibility_rate']) ? $_GET['first_responsibility_rate'] : $filter_value['first_responsibility_rate']; } ?>"
                                            placeholder="<?= lang('warrantly_refund', 'first_responsibility_rate_filter') ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '');">

                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>         
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="determined_responsibility_rate"><?= lang('warrantly_refund', 'determined_responsibility_rate') ?> (%)</label>
                                    <div class="input-group">
                                        <input name="determined_responsibility_rate" id="determined_responsibility_rate" class="form-control"
                                            value="<?php if(isset($_GET['determined_responsibility_rate']) || isset($filter_value['determined_responsibility_rate'])){ 
                                                echo isset($_GET['determined_responsibility_rate']) ? $_GET['determined_responsibility_rate'] : $filter_value['determined_responsibility_rate']; } ?>"
                                            placeholder="<?= lang('warrantly_refund', 'determined_responsibility_rate_filter') ?>"  oninput="this.value = this.value.replace(/[^0-9]/g, '');"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="piece_quantity"><?= lang('warrantly_refund', 'piece_quantity') ?></label>
                                    <div class="input-group">
                                        <input name="piece_quantity" id="piece_quantity" class="form-control"
                                            value="<?php if(isset($_GET['piece_quantity']) || isset($filter_value['piece_quantity'])){ 
                                                echo isset($_GET['piece_quantity']) ? $_GET['piece_quantity'] : $filter_value['piece_quantity']; } ?>"
                                            placeholder="<?= lang('warrantly_refund', 'piece_quantity_filter') ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '');"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="agreement_created_date"><?= lang('warrantly_refund', 'agreement_created_date') ?></label>
                                    <div class="input-group">
                                        <div class="input-group">
                                        <input name="agreement_created_date" id="agreement_created_date" class="form-control" autocomplete="off" 
                                            placeholder="<?php if(lang('type','type_language') == 'TR'){ echo "gg.aa.yyyy>gg.aa.yyyy"; }else{ echo "dd.mm.yyyy>dd.mm.yyyy";}?>"
                                            value="<?= @$_GET['agreement_created_date'] ?>" />
                                        <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="mdi mdi-calendar-multiselect"></i>
                                                </span>
                                        </div>
                                    </div>
                                    </div>
                                </div>  
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="agreement_confirmation_date"><?= lang('warrantly_refund', 'agreement_confirmation_date') ?></label>
                                    <div class="input-group">
                                        <input name="agreement_confirmation_date" id="agreement_confirmation_date" class="form-control" autocomplete="off" 
                                            placeholder="<?php if(lang('type','type_language') == 'TR'){ echo "gg.aa.yyyy>gg.aa.yyyy"; }else{ echo "dd.mm.yyyy>dd.mm.yyyy";}?>"
                                            value="<?= @$_GET['agreement_confirmation_date'] ?>" />
                                        <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="mdi mdi-calendar-multiselect"></i>
                                                </span>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="agreement_validity_date"><?= lang('warrantly_refund', 'agreement_validity_date') ?></label>
                                    <div class="input-group">
                                        <input name="agreement_validity_date" id="agreement_validity_date" class="form-control" autocomplete="off" 
                                            placeholder="<?php if(lang('type','type_language') == 'TR'){ echo "gg.aa.yyyy>gg.aa.yyyy"; }else{ echo "dd.mm.yyyy>dd.mm.yyyy";}?>"
                                            value="<?= @$_GET['agreement_validity_date'] ?>" />
                                        <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="mdi mdi-calendar-multiselect"></i>
                                                </span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-12 input-container pt-2">
                                    <label class="input-label" for="fk_product_group_id"><?= lang('warrantly_refund', 'product_group_id') ?></label>
                                    <div class="input-group">
                                        <select class="form-control" name="fk_product_group_id" id="fk_product_group_id" aria-label="Default select example">
                                            <option disabled selected="selected" value=""><?= lang('general', 'select') ?></option>
                                            <?php foreach($product_group_list as $idx => $product_group){ ?>
                                                <option value="<?= $product_group['product_group_id'] ?>"
                                                    <?php if(
                                                        (isset($_GET['fk_product_group_id']) && $_GET['fk_product_group_id'] == $idx) || 
                                                        (isset($filter_value['fk_product_group_id']) && $filter_value['fk_product_group_id'] == $idx)
                                                        ) { echo "selected"; } ?>>
                                                    <?php echo lang('type', 'language_type') == "EN" ? $product_group['description_en'] : $product_group['description'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-subtitles-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>              
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="text-center mb-0"><span class="btn btn-danger"
                                style="background-color: rgba(253, 57, 122, 0.1);color: #fd397a; border-color:rgba(253, 57, 122, 0.1) ;"
                                onclick="resetFilters()"><i
                                    class="mdi mdi-refresh"></i><?= \Illuminate\Helpers\Language::get('general', 'reset'); ?></span>
                        </p>
                        <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i>
                            <?= lang('general', 'search') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= asset('vendor/air-datepicker-2.2.3/js/datepicker.js') ?>"></script>
    <?php $this->inc('components/warrantly_refund_script'); ?>
    <script src="<?= asset('vendor/datetimepicker-master/mmnt.js') ?>"></script>
    <!-- React ve ReactDOM'u CDN'den ekleyin -->
    <script src="https://unpkg.com/react@17/umd/react.production.min.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js" crossorigin></script>
    <!-- Babel'i ekleyin (JSX'i desteklemek için) -->
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <!-- Add arrangement list of react table in js page -->
    <script type="text/babel" src="<?= ass('js/component-reactjs/warrantly-refund/agreement/AgreementList.js') ?>"></script>
</body>
</html>
<script>
    $('#agreement_validity_date').datepicker({
        language: getLang(), //'tr',
        range: true,
        multipleDatesSeparator: '>',
        position: 'top left',
        onSelect: function (formattedDate, date, inst) {
            $(inst.el)
                .parent('.input-group')
                .append('<i class="mdi mdi-close period-reset"></i>');
        }
    });
</script>
<script>
    $('#agreement_created_date').datepicker({
        language: getLang(), //'tr',
        range: true,
        multipleDatesSeparator: '>',
        position: 'top left',
        onSelect: function (formattedDate, date, inst) {
            $(inst.el)
                .parent('.input-group')
                .append('<i class="mdi mdi-close period-reset"></i>');
        }
    });
</script>
<script>
    $('#agreement_confirmation_date').datepicker({
        language: getLang(), //'tr',
        range: true,
        multipleDatesSeparator: '>',
        position: 'top left',
        onSelect: function (formattedDate, date, inst) {
            $(inst.el)
                .parent('.input-group')
                .append('<i class="mdi mdi-close period-reset"></i>');
        }
    });
</script>
<script> 

    function resetQuickFilter(){
        // Mevcut URL'i al
        var currentUrl = window.location.href;

        // URL'den parametreleri çıkar
        var urlWithoutParams = currentUrl.split('?')[0];

        // Yeni URL oluştur
        var newUrl = urlWithoutParams + '?check=2';

        // Sayfayı yeniden yükle
        window.location.href = newUrl;
    }

</script>
<script>

    function newPaginate(sortBy, sortOrder, pageSize, page){
        
        // Mevcut URL'i al ve URL nesnesine dönüştür
        let url = new URL(document.location);

        // URL parametrelerini ayarla
        url.searchParams.set('sortField', sortBy);
        url.searchParams.set('sortDirection', sortOrder);
        url.searchParams.set('totalPage', pageSize);
        url.searchParams.set('page', page);

        // Sayfayı yeni URL'ye yönlendir
        window.location.href = url.toString();
        
    }

</script>
<script>

    function resetFilters() {

        document.getElementById('agreement_number').value = '';
        document.getElementById('customer_party_id').value = '';
        document.getElementById('related_plants').value = '';
        document.getElementById('project_name').value = '';
        document.getElementById('first_responsibility_rate').value = '';
        document.getElementById('determined_responsibility_rate').value = '';
        document.getElementById('piece_quantity').value = '';
        document.getElementById('agreement_created_date').value = '';
        document.getElementById('agreement_confirmation_date').value = '';
        document.getElementById('agreement_validity_date').value = '';
        document.getElementById('fk_product_group_id').value = '';
                
    }

</script>