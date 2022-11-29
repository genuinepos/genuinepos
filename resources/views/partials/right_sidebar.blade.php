<div id="rightSidebar">
    <div class="sidebar-container position-relative">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="text-white">@lang('menu.user_profile') </h2>
            <div id="closeRightSidebar"><a href="#" style="color: #fff;"><i class="far fa-times-circle fs-4"></i></a></div>
        </div>
        <div class="py-3 ">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="far fa-user fs-3"></i></span></li>
                <li class="my-1 me-2 ms-1 text-white py-2" style="font-size: 12px">
                    {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}
                    @if (auth()->user()->role_type == 1)
                        (@lang('menu.super_admin'))
                    @elseif(auth()->user()->role_type == 2)
                    (@lang('menu.admin'))
                    @else
                        {{ auth()->user()->roles()->first()->name }}
                    @endif
                </li>
            </ul>
        </div>
        <div class="border-top py-3">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="far fa-user-circle fs-3"></i></span></li>
                <li class="my-1 me-2 ms-1">
                    <a href="{{ route('users.profile.view', auth()->user()->id) }}">
                        <p class="title text-white">@lang('menu.my_profile')</p>
                        <small class="email text-white">{{ auth()->user()->email }}</small>
                    </a>
                </li>
            </ul>
        </div>
        <div class="border-top py-3">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="fas fa-user fs-3"></i></span></li>
                <li class="my-1 me-2 ms-1">
                    <a href="{{ route('users.profile.index') }}">
                        <p class="title text-white">@lang('menu.change_profile')</p>
                        <small class="email text-white">@lang('menu.update_or_change_password')</small>
                    </a>
                </li>
            </ul>
        </div>

        <div class="border-top py-3">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><small class=""><i class="far fa-comment-alt fs-3"></i></small></li>
                <li class="my-2 me-2 ms-1">
                    <a href="#">
                        <p class="title text-white">@lang('menu.feedback')</p>
                    </a>
                </li>
            </ul>
        </div>
        <div class="border-top py-3">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><small class=""><i class="fas fa-book fs-3"></i></small></li>
                <li class="my-2 me-2 ms-1">
                    <a href="#">
                        <p class="title text-white">@lang('menu.documentation')</p>
                    </a>
                </li>
            </ul>
        </div>

        <div class=" position-absolute" style="bottom: 2px; left: 0; right: 0; padding: 20px 0px 28px 0px; border-top: 1px solid #fff;">
            <ul class="d-flex justify-content-around">
                <li><a href="{{ route('settings.general.index') }}" class="text-white"><span><i class="fas fa-cog fa-2x"></i></span></a></li>

                <li>
                    <a href="#" class="text-white" id="fullscreen"  onclick="toggleFullScreen(document.body)"><i class="fas fa-expand fa-2x"></i></a>
                </li>

                <li><a href="#" class="text-white" id="logout_option"><span><i class="fas fa-power-off fa-2x"></i></span></a></li>
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
                <h6 class="modal-title" id="sidebarShortcutModalLabel">Add a shortcut to sidebar</h6>
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
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">@lang('menu.cancel')</button>
                        <button type="reset" class="btn btn-sm btn-success save-shortcut" data-bs-dismiss="modal" disabled>@lang('menu.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#openRightSidebar").on('click', function(){
                $("#rightSidebar").toggleClass("open");
            });
            $("#closeRightSidebar").on('click', function(){
                $("#rightSidebar").removeClass("open");
            });



            $("#shortcutUrl").on("change", function() {
                if($("#shortcutUrl").is(":valid")) {
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
                if($("#shortcutName").is(":valid")) {
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
                $(".edit-shortcut").on("click", function(){
                    $(this).parents(".shorcut-box").find(".shortcut-modal").addClass("show");
                });
                $(".close-shortcut-modal, .change-shortcut").on("click", function(){
                    $(this).parents(".shorcut-box").find(".shortcut-modal").removeClass("show");
                });
                $(".delete-shortcut").on("click", function(){
                    $(this).parents(".shorcut-box").remove();
                });


                $(".shortcut-modal").find(".shortcut-name").on("change", function(){
                    if($(this).is(":valid")) {
                        $(this).parents(".shorcut-box").find("a").attr('title', $(this).val());
                    } else {
                        $(this).parents(".shorcut-box").find("a").attr('title', $(this).parents(".shorcut-box").find(".shortcut-url").val());
                    }
                });
                $(".shortcut-modal").find(".shortcut-url").on("change", function(){
                    if($(this).is(":valid")) {
                        $(this).parents(".shorcut-box").find(".icon").attr('src', 'https://www.google.com/s2/favicons?domain=' + $(this).val());
                        $(this).parents(".shorcut-box").find("a").attr('href', $(this).val());
                    } else {
                        $(this).parents(".shorcut-box").remove();
                    }
                });
            });
        });
    </script>
@endpush
