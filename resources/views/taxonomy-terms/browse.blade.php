@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->display_name_plural)

@section('css')
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ voyager_asset('zTree_v3/css/metroStyle/metroStyle.css') }}"/>
    <style type="text/css">
        #edit-term {
            position: fixed;
            right: 30px;
            overflow-y: auto;
        }
        .alert.alert-mini {
            padding: 10px;
        }
        .voyager .btn.btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: .2rem;
        }
    </style>
@stop
@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="icon voyager-categories"></i> {{ $vocabulary->name }}
        </h1>
        @can('add', app($dataType->model_name))
            <a href="#" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
        @can('delete', app($dataType->model_name))
        <a class="btn btn-danger" id="bulk_delete_btn"><i class="voyager-trash"></i> <span>{{ __('voyager::generic.bulk_delete') }}</span></a>

        {{-- Bulk delete modal --}}
        <div class="modal modal-danger fade" tabindex="-1" id="bulk_delete_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            <i class="voyager-trash"></i> {{ __('voyager::generic.are_you_sure_delete') }} <span id="bulk_delete_count"></span> <span id="bulk_delete_display_name"></span>?
                        </h4>
                    </div>
                    <div class="modal-body" id="bulk_delete_modal_body">
                        <p>{{ __('voyager::taxonomy.irreversible') }}</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('voyager.'.$dataType->slug.'.index', [$vid]) }}/0" id="bulk_delete_form" method="POST">
                            {{ method_field("DELETE") }}
                            {{ csrf_field() }}
                            <input type="hidden" name="ids" id="bulk_delete_input" value="">
                            <input type="submit" class="btn btn-danger pull-right delete-confirm"
                                   value="{{ __('voyager::generic.bulk_delete_confirm') }} {{ strtolower($dataType->display_name_plural) }}">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                            {{ __('voyager::generic.cancel') }}
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <script>
            window.onload = function () {
                // Bulk delete selectors
                var $bulkDeleteBtn = $('#bulk_delete_btn');
                var $bulkDeleteModal = $('#bulk_delete_modal');
                var $bulkDeleteCount = $('#bulk_delete_count');
                var $bulkDeleteDisplayName = $('#bulk_delete_display_name');
                var $bulkDeleteInput = $('#bulk_delete_input');
                // Reposition modal to prevent z-index issues
                $bulkDeleteModal.appendTo('body');
                // Bulk delete listener
                $bulkDeleteBtn.click(function () {
                    var ids = [];
                    var $checkedBoxes = zTreeObj.getCheckedNodes();
                    console.log($checkedBoxes);
                    var count = $checkedBoxes.length;
                    if (count) {
                        // Reset input value
                        $bulkDeleteInput.val('');
                        // Deletion info
                        var displayName = count > 1 ? '{{ $dataType->display_name_plural }}' : '{{ $dataType->display_name_singular }}';
                        displayName = displayName.toLowerCase();
                        $bulkDeleteCount.html(count);
                        $bulkDeleteDisplayName.html(displayName);
                        // Gather IDs
                        $.each($checkedBoxes, function (index, node) {
                            if (node.check_Child_State === 1 || node.check_Child_State === 0) {
                                return true
                            }
                            var value = node.id;
                            ids.push(value);
                        })
                        if (ids.length <= 0) {
                            toastr.warning('{{ __('voyager::generic.bulk_delete_nothing') }}');
                            return false;
                        }
                        console.log(ids);
                        // Set input value
                        $bulkDeleteInput.val(ids);
                        // Show modal
                        $bulkDeleteModal.modal('show');
                    } else {
                        // No row selected
                        toastr.warning('{{ __('voyager::generic.bulk_delete_nothing') }}');
                    }
                });
            }
        </script>

        @endcan
        @can('edit', app($dataType->model_name))
            @if(isset($dataType->order_column) && isset($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('add', app($dataType->model_name))
            <a class="btn btn-primary btn-upload" href="#">
                <i class="voyager-upload"> <span>{{ __('voyager::taxonomy.import') }}</i></span>
            </a>
        @endcan
        @include('voyager::multilingual.language-selector')
        <div class="panel panel-bordered panel-importer" style="display: none">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3><i class="voyager-upload"></i>{{ __('voyager::taxonomy.import') }}</h3>
                        <form action="{{ route('voyager.taxonomy-terms.import', ['vid' => $vid]) }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group col-md-12">
                                <label for="upload_file">{{ __('voyager::form.type_file') }}</label>
                                <input type="file" name="upload_file" id="upload_file">
                                <p class="help-block">{{ __('voyager::taxonomy.upload_files_in_dot_xlsx_format') }}</p>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="start_line">{{ __('voyager::taxonomy.start_line') }} </label>
                                <input type="number" name="start_line" value="2" id="start_line"/>
                            </div>
                            <button type="submit" class="btn btn-success">{{ __('voyager::taxonomy.import') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="min-height: 600px">
                    <div class="panel-body">
                        <div class="row">
                            <div class="alert-order alert alert-warning alert-mini fade in" role="alert"><span>*排序已经改变, 点击保存按钮后生效</span> <a
                                        class="btn btn-sm btn-success btn-save-order" href="javascript:;">{{ __('voyager::generic.save') }}</a>  <a
                                        class="btn btn-sm btn-warning btn-reset-order" href="javascript:;">{{ __('voyager::taxonomy.reset') }}</a></div>
                            <div class="col-md-7">
                                <div>{{ __('voyager::taxonomy.total_items', ['total' => $total]) }}</div>
                                <div id="termsTree" class="terms-tree ztree">
                                </div>
                            </div>
                            <div class="col-md-5" id="edit-term">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->display_name_singular) }}?</h4>
                </div>
                <div class="modal-body">
                    <p>{{ __('voyager::taxonomy.irreversible') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div id="script"></div>
@stop



@section('javascript')
    <script src="{{ voyager_asset('zTree_v3/js/jquery.ztree.all.min.js') }}"></script>
    <script src="{{ voyager_asset('jquery.form.min.js') }}"></script>
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script>
        function addNewTerm(parent_id) {
            var addUrl = '{{ route('voyager.'.$dataType->slug.'.create', ['vid' => $vid]) }}';
            addUrl = addUrl+(parent_id ? "?parent_id="+parent_id : "");
            $("#edit-term").load(addUrl, function() {
                $("#edit-term").attr('p-href', addUrl)
            });
        }

        var currentNode;
        $(document).ready(function () {
            // Add new term url.


            $("#edit-term").css({maxHeight:($('body').height()-210)});
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => [],
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [['targets' => -1, 'searchable' =>  false, 'orderable' => false]],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });
        });

        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', ['vid' => $vid, 'id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        $('.btn-upload').on('click', function() {
            $('.panel-importer').stop().slideToggle();
        })

        $(".btn-add-new").on('click', function() {
            addNewTerm();
            return false;
        })

        // zTree
        var treeIdAttr = "termsTree";
        var editNodeUrl = "{{ route('voyager.taxonomy-terms.edit', [$vid, '__id']) }}";
        var setting = {
            async: {
                enable: true,
                url: "{{route('voyager.taxonomy-terms.index', ['vid' => $vid])}}",
                type: "get",
                autoParam:["id", "name=n", "level=lv"],
                otherParam:{"otherParam":"zTreeAsyncTest"},
                dataFilter: filter
            },
            view: {
                dblClickExpand: false,
                expandSpeed:"",
                addHoverDom: addHoverDom,
                removeHoverDom: removeHoverDom,
                selectedMulti: false
            },
            check: {
                enable: true,
                chkboxType:{ "Y": "s", "N": "s" }
            },
            edit: {
                enable: true,
                showRenameBtn: false,
                drag: {
                    isCopy: false
                }
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                beforeRemove: beforeRemove,
//                beforeRename: beforeRename,
//                beforeEditName: beforeEditName,
                onClick: onClickNode,
                beforeRemove: beforeRemove
            }
        };

        function filter(treeId, parentNode, childNodes) {
            if (!childNodes) return null;
            for (var i=0, l=childNodes.length; i<l; i++) {
                childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
            }
            return childNodes;
        }
        function beforeRemove(treeId, treeNode) {
            var zTree = $.fn.zTree.getZTreeObj(treeIdAttr);
            zTree.selectNode(treeNode);
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', ['vid' => $vid, 'id' => '__id']) }}'.replace('__id', treeNode.id);
            $('#delete_modal').modal('show');
            return false;
        }
        function beforeRename(treeId, treeNode, newName) {
            if (newName.length == 0) {
                setTimeout(function() {
                    var zTree = $.fn.zTree.getZTreeObj(treeIdAttr);
                    zTree.cancelEditName();
                    alert("Node name can not be empty.");
                }, 0);
                return false;
            }
            return true;
        }
        function onClickNode(e, treeId, treeNode) {
            var editUrl = editNodeUrl.replace('__id', treeNode.id);
            currentNode = treeNode.getParentNode();
            $("#edit-term").load(editUrl, function() {
                $("#edit-term").attr('p-href', editUrl);
            })
            return false;
        }
        function beforeEditName(treeId, treeNode) {
            zTreeObj.selectNode(treeNode)

            var editUrl = editNodeUrl.replace('__id', treeNode.id);
            currentNode = treeNode.getParentNode();
            $("#edit-term").load(editUrl, function() {
                $("#edit-term").attr('p-href', editUrl);
            })
            return false;
        }

        var newCount = 1;
        function addHoverDom(treeId, treeNode) {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                    + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_"+treeNode.tId);
            if (btn) btn.bind("click", function(){
                zTreeObj.selectNode(treeNode)
                addNewTerm(treeNode.id);
                currentNode = treeNode;
                return false;
//                var zTree = $.fn.zTree.getZTreeObj(treeIdAttr);
//                zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
//                return false;
            });
        };
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_"+treeNode.tId).unbind().remove();
        };

        $(".btn-save-order").on("click", function() {
            var nodes = zTreeObj.getNodes();
            console.log(nodes);
            // zTreeObj.reAsyncChildNodes(null, 'refresh');
        });

        $(".btn-reset-order").on("click", function() {
            zTreeObj.reAsyncChildNodes(null, 'refresh');
            $(".alert-order").fadeOut("slow");
        });

        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#"+treeIdAttr), setting);
        });

    </script>
@stop
