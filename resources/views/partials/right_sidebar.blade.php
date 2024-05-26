<style>
    .branch_switcher {
        flex: 1;
    }

    .branch_switcher .select-dropdown select {
        width: 100%;
        color: #fff !important;
    }
</style>
<div id="rightSidebar">
    <div class="sidebar-container position-relative">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="text-white">{{ __('User Profile') }}</h2>
            <div id="closeRightSidebar"><a href="#" style="color: #fff;"><i class="far fa-times-circle fs-4"></i></a></div>
        </div>

        <div class="border-top py-1">
            <ul class="d-flex flex-row justify-content-start">
                @if (auth()->user()->can('has_access_to_all_area'))
                    @php
                        $branchService = new App\Services\Setups\BranchService();
                        $branches = $branchService->switchableBranches();
                    @endphp
                    <li class="icon text-white"><span class=""><i class="fa-solid fa-shop"></i></span></li>
                    <li class="my-1 me-2 ms-1 branch_switcher">
                        <form id="change_branch_form" action="{{ route('users.change.branch') }}">
                            @csrf
                            <div class="select-dropdown">
                                <select name="branch_id" id="switch_branch_id">
                                    <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>

                                    @foreach ($branches as $branch)

                                        <option {{ auth()->user()->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">
                                            @php
                                                $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                $branchCode = '-' . $branch->branch_code;
                                            @endphp
                                            {{ $branchName . $areaName . $branchCode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </li>
                @endif
            </ul>
        </div>

        <div class="py-1">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="far fa-user"></i></span></li>
                <li class="my-1 me-2 ms-1 text-white py-1" style="font-size: 12px">
                    {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}
                    @if (auth()->user()->role_type == 1)
                        ({{ __('Super-Admin') }})
                    @elseif(auth()->user()->role_type == 2)
                        ({{ __('Admin') }})
                    @else
                        {{ auth()->user()?->roles()?->first()?->name }}
                    @endif
                </li>
            </ul>
        </div>

        <div class="border-top py-1">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="far fa-user-circle"></i></span></li>
                <li class="my-1 me-2 ms-1">
                    <a href="{{ route('users.profile.view', auth()->user()->id) }}">
                        <p class="title text-white">{{ __('My Profile') }}</p>
                        <small class="email text-white">{{ auth()->user()->email }}</small>
                    </a>
                </li>
            </ul>
        </div>

        <div class="border-top py-1">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="fas fa-user"></i></span></li>
                <li class="my-1 me-2 ms-1">
                    <a href="{{ route('users.profile.index') }}">
                        <p class="title text-white">{{ __('Change Profile') }}</p>
                        <small class="email text-white">{{ __('Update or change password') }}</small>
                    </a>
                </li>
            </ul>
        </div>

        <div class="border-top py-1">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><small class=""><i class="far fa-comment-alt"></i></small></li>
                <li class="my-2 me-2 ms-1">
                    <a href="{{ route('feedback.index') }}">
                        <p class="title text-white">{{ __('Feedback') }}</p>
                    </a>
                </li>
            </ul>
        </div>
        <div class="border-top py-1">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><small class=""><i class="fas fa-book"></i></small></li>
                <li class="my-2 me-2 ms-1">
                    <a href="#">
                        <p class="title text-white">{{ __('Documentation') }}</p>
                    </a>
                </li>
            </ul>
        </div>

        <div class="position-absolute bottom-btn-group" style="bottom: 0; left: 0; right: 0; border-top: 1px solid #fff;">
            <ul class="d-flex">
                <li><a href="#" class="text-white menu-theme"><span><i class="fas fa-sun"></i></span><span id="themeNameText">{{ __('Light Nav') }}</span></a></li>
                <li class="d-lg-block d-none"><a href="{{ route('settings.general.index') }}" class="text-white"><span><i class="fas fa-cog"></i></span><span>{{ __('Settings') }}</span></a></li>

                <li>
                    <a href="#" class="text-white" id="btnFullscreen"><span><i class="fas fa-expand"></i></span><span>{{ __('Fullscreen') }}</span></a>
                </li>

                <li><a href="#" class="text-white bg-danger border-danger" id="logout_option"><span><i class="fas fa-power-off"></i></span><span>{{ __('Logout') }}</span></a></li>
            </ul>
        </div>
    </div>
    <div class="shortcut-bar d-lg-block d-none">
        <div class="shorcut-box add-new-box">
            <span class="shortcut-wrap" data-bs-toggle="tooltip" data-bs-title="Add a new shortcut" data-bs-placement="right">
                <a href="#" data-bs-toggle="modal" data-bs-target="#sidebarShortcutModal">
                    <i class="fas fa-plus"></i>
                </a>
            </span>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sidebarShortcutModal" tabindex="-1" aria-labelledby="sidebarShortcutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="sidebarShortcutModalLabel">{{ __('Add Shortcut Menus') }}</h6>
                <a href="#" role="button" type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" id="shortcutName" placeholder="Shortcut name" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="url" class="form-control" id="shortcutUrl" placeholder="Shortcut url" required>
                    </div>
                    <div class="form-group d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="reset" class="btn btn-sm btn-success save-shortcut" data-bs-dismiss="modal" disabled>{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#openRightSidebar").on('click', function() {
                $("#rightSidebar").toggleClass("open");
            });

            $("#closeRightSidebar").on('click', function() {
                $("#rightSidebar").removeClass("open");
            });

            $("#shortcutUrl").on("change", function() {
                if ($("#shortcutUrl").is(":valid")) {
                    $(".save-shortcut").prop("disabled", false);
                } else {
                    $(".save-shortcut").prop("disabled", true);
                }
            })

            $(".save-shortcut").on("click", function() {
                $(".shortcut-bar").prepend(`
                <div class='shorcut-box'>
                    <div class='dropdown'>
                        <button class='shortcut-action' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class='dropdown-menu'>
                            <li><button class='edit-shortcut'>Edit</button></li>
                            <li><button class='delete-shortcut'>Remove</button></li>
                        </ul>
                    </div>
                    <span class='shortcut-wrap'>
                        <a href='#' target='blank'><img class='icon' src=''></a>
                    </span>
                    <div class='shortcut-modal modal-dialog'>
                        <div class='modal-header'>
                            <h6 class='modal-title'>Edit shortcut</h6>
                            <a href='#' class='close-btn close-shortcut-modal'><span class='fas fa-times'></span></a>
                        </div>
                        <div class='modal-body'>
                            <form>
                                <div class='form-group mb-3'>
                                    <input type='text' class='form-control shortcut-name' placeholder='Shortcut name' required>
                                </div>
                                <div class='form-group mb-3'>
                                    <input type='url' class='form-control shortcut-url' placeholder='Shortcut url' required>
                                </div>
                                <div class='form-group d-flex justify-content-end gap-2'>
                                    <button type='button' class='btn btn-sm btn-secondary close-shortcut-modal'>Cancel</button>
                                    <button type='button' class='btn btn-sm btn-success change-shortcut'>@lang('menu.save')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                `);

                $(".shortcut-bar .shorcut-box:first-child").find(".icon").attr('src', 'https://www.google.com/s2/favicons?domain=' + $("#shortcutUrl").val());
                $(".shortcut-bar .shorcut-box:first-child").find("a").attr('href', $("#shortcutUrl").val());
                $(".shortcut-bar .shorcut-box:first-child .shortcut-modal").find(".shortcut-url").val($("#shortcutUrl").val());
                if ($("#shortcutName").is(":valid")) {
                    var shortcutName = $("#shortcutName").val();
                    $(".shortcut-bar .shorcut-box:first-child").find("a").attr("title", shortcutName);
                    $(".shortcut-bar .shorcut-box:first-child .shortcut-modal").find(".shortcut-name").val(shortcutName);
                } else {
                    var shortcutUrl = $("#shortcutUrl").val();
                    $(".shortcut-bar .shorcut-box:first-child").find("a").attr("title", shortcutUrl);
                    $(".shortcut-bar .shorcut-box:first-child .shortcut-modal").find(".shortcut-name").val("");
                }
                $("#shortcutName").val('');
                $("#shortcutUrl").val('');
                $(".edit-shortcut").on("click", function() {
                    $(this).parents(".shorcut-box").find(".shortcut-modal").addClass("show");
                });
                $(".close-shortcut-modal, .change-shortcut").on("click", function() {
                    $(this).parents(".shorcut-box").find(".shortcut-modal").removeClass("show");
                });
                $(".delete-shortcut").on("click", function() {
                    $(this).parents(".shorcut-box").remove();
                });

                $(".shortcut-modal").find(".shortcut-name").on("change", function() {
                    if ($(this).is(":valid")) {
                        $(this).parents(".shorcut-box").find("a").attr('title', $(this).val());
                    } else {
                        $(this).parents(".shorcut-box").find("a").attr('title', $(this).parents(".shorcut-box").find(".shortcut-url").val());
                    }
                });
                $(".shortcut-modal").find(".shortcut-url").on("change", function() {
                    if ($(this).is(":valid")) {
                        $(this).parents(".shorcut-box").find(".icon").attr('src', 'https://www.google.com/s2/favicons?domain=' + $(this).val());
                        $(this).parents(".shorcut-box").find("a").attr('href', $(this).val());
                    } else {
                        $(this).parents(".shorcut-box").remove();
                    }
                });
            });

            function toggleFullscreen(elem) {
                elem = elem || document.documentElement;
                if (!document.fullscreenElement && !document.mozFullScreenElement &&
                    !document.webkitFullscreenElement && !document.msFullscreenElement) {
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    } else if (elem.mozRequestFullScreen) {
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    }

                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
            }

            document.getElementById('btnFullscreen').addEventListener('click', function() {
                toggleFullscreen();
            });

            // document.getElementById('exampleImage').addEventListener('click', function() {
            //     toggleFullscreen(this);
            // });
        });

        var selectedBranchId = $('#switch_branch_id').val();
        $(document).on('change', '#switch_branch_id', function(e) {

            $.confirm({
                'title': "{{ __('Confirmation') }}",
                'content': "{{ __('Are you sure?') }}",
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#change_branch_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            $('#switch_branch_id').val(selectedBranchId);
                        }
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#change_branch_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success(data);
                    window.location.reload();
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>
@endpush
