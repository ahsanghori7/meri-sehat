<aside class="main-sidebar fixed offcanvas shadow" data-toggle='offcanvas'>
    <section class="sidebar">
        <div class="w-80px mt-3 mb-3 ml-3">
            <img src="/assets/img/basic/logo.png" alt="">
        </div>
        <div class="relative">
            <a data-toggle="collapse" href="#userSettingsCollapse" role="button" aria-expanded="false"
               aria-controls="userSettingsCollapse" class="btn-fab btn-fab-sm absolute fab-right-bottom fab-top btn-primary shadow1 ">
                <i class="icon icon-cogs"></i>
            </a>
            <div class="user-panel p-3 light mb-2">
                <div>
                    <div class="float-left image">
                        <img class="user_avatar" src="/assets/img/dummy/u2.png" alt="User Image">
                    </div>
                    <div class="float-left info">
                        <h6 class="font-weight-light mt-2 mb-1">Admin</h6>
                        <a href="#"><i class="icon-circle text-primary blink"></i> Online</a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="collapse multi-collapse" id="userSettingsCollapse">
                    <div class="list-group mt-3 shadow">
                        <a href="/admin/manage-password" class="list-group-item list-group-item-action"><i
                            class="mr-2 icon-security text-purple"></i>Manage Password</a>
                        <a href="/admin/logout" class="list-group-item list-group-item-action"><i
                                class="mr-2 icon-lock3 text-purple"></i>Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header"><strong>MAIN NAVIGATION</strong></li>
            <li class="treeview"><a href="#"><i class="icon icon-account_box light-primary-text s-18"></i>Users<i
                class="icon icon-angle-left s-18 pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/admin/user/view"><i class="icon icon-circle-o"></i>All Users</a>
                    </li>
                </ul>
            </li>
            <li class="treeview"><a href="#"><i class="icon icon-settings2 light-primary-text s-18"></i>Settings<i
                class="icon icon-angle-left s-18 pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/admin/settings/list"><i class="icon icon-circle-o"></i>List</a></li>
                    {{-- <li><a href="/admin/settings/add"><i class="icon icon-circle-o"></i>Add</a></li> --}}
                </ul>
            </li>
            <li class="treeview"><a href="#"><i class="icon icon-settings2 icon-newspaper-o s-18"></i>Site Contents<i
                class="icon icon-angle-left s-18 pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/admin/site-content"><i class="icon icon-circle-o"></i>List</a></li>
                    <li><a href="/admin/site-content/add"><i class="icon icon-circle-o"></i>Add</a></li>
                </ul>
            </li>
            <li class="treeview"><a href="#"><i class="icon icon-settings2 icon-newspaper s-18"></i>Info Modal Contents<i
                class="icon icon-angle-left s-18 pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/admin/info-modal"><i class="icon icon-circle-o"></i>List</a></li>
                    <li><a href="/admin/info-modal/add"><i class="icon icon-circle-o"></i>Add</a></li>
                </ul>
            </li>

            <li class="treeview"><a href="#"><i class="icon icon-location_city s-18"></i>Cities<i
                class="icon icon-angle-left s-18 pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/admin/cities"><i class="icon icon-circle-o"></i>List</a></li>
                    <li><a href="/admin/city/add"><i class="icon icon-circle-o"></i>Add</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>