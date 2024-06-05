  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-1 sidebar-dark-lightblue sidebar-gardians">
      <!-- Brand Logo -->
      <a href="{{ route('main') }}" class="brand-link">
          @if ($isRole->role_type == 'Partner')
              <img src="{{ $isRole->logo }}"class="brand-image">
              <span class="brand-text text-center"> STOCK PROJECT </span>
          @else
              <img src="{{ url('uploads/logo.png') }}" alt="vbeyond" class="brand-image elevation-0" style="opacity: .8">
              <span class="brand-text text-center"> VBEYOND STOCK</span>
          @endif
      </a>



      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">

                  <img src="{{ url('uploads/avatar.png') }}" class="img-circle elevation-2" alt="User Image">

              </div>
              <div class="info">
                  <a href="#" class="d-block">คุณ {{ $dataLoginUser['email'] }}</a>
              </div>
          </div>

          @if ($isRole->role_type == 'Partner')
              <!-- Sidebar Partner -->
              <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                      data-accordion="false">
                      <li class="nav-item">
                          <a href="{{ route('main') }}"
                              class="nav-link {{ request()->routeIs('main') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                  แดชบอร์ด
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('project') }}"
                              class="nav-link {{ request()->routeIs('project') ? 'active' : '' }}
                        {{ request()->routeIs('project.detail') ? 'active' : '' }}
                        {{ request()->routeIs('project.edit') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-building"></i>
                              <p>
                                  โครงการ
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('room') }}"
                              class="nav-link {{ request()->routeIs('room') ? 'active' : '' }} {{ request()->routeIs('room.search') ? 'active' : '' }} {{ request()->routeIs('room.edit') ? 'active' : '' }} {{ request()->routeIs('room.search.partner') ? 'active' : '' }} {{ request()->routeIs('room.edit.partner') ? 'active' : '' }} {{ request()->routeIs('room.booking') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-home"></i>
                              <p>
                                  ห้อง
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ url('uploads/howto/manual_partner_stock.pdf') }}" class="nav-link"
                              target="_blank">
                              <i class="nav-icon fas fa-book"></i>
                              <p>
                                  คู่มือใช้งาน
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>




                      {{-- <li class="nav-header" style="background-color: rgba(255, 23, 35, 0.486)">สำหรับผู้ดูแลระบบ</li> --}}

                      <li class="nav-item">
                          {{-- <a style="background-color: rgba(255, 23, 35, 0.486)" href="{{route('logoutUser')}}" class="nav-link {{ request()->routeIs('logoutUser') ? 'active' : '' }}"> --}}
                          <a style="" href="{{ route('logoutUser') }}"
                              class="nav-link {{ request()->routeIs('logoutUser') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-sign-out"></i>
                              <p>
                                  ออกจากระบบ
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>



                  </ul>
              </nav>
          @elseif (empty($isRole->role_type))
              <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                      data-accordion="false">
                      <li class="nav-item">
                          <a href="{{ route('main') }}"
                              class="nav-link {{ request()->routeIs('main') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                  แดชบอร์ด {{ $isRole->role_type }}
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>

                      <li class="nav-item">
                          {{-- <a style="background-color: rgba(255, 23, 35, 0.486)" href="{{route('logoutUser')}}" class="nav-link {{ request()->routeIs('logoutUser') ? 'active' : '' }}"> --}}
                          <a style="" href="{{ route('logoutUser') }}"
                              class="nav-link {{ request()->routeIs('logoutUser') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-sign-out"></i>
                              <p>
                                  ออกจากระบบ
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>



                  </ul>
              </nav>
          @else
              <!-- Sidebar Vbeyond -->
              <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                      data-accordion="false">
                      <li class="nav-item">
                          <a href="{{ route('main') }}"
                              class="nav-link {{ request()->routeIs('main') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-tachometer-alt"></i>
                              <p>
                                  แดชบอร์ด
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('project') }}"
                              class="nav-link {{ request()->routeIs('project') ? 'active' : '' }}
                  {{ request()->routeIs('project.detail') ? 'active' : '' }}
                  {{ request()->routeIs('project.edit') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-building"></i>
                              <p>
                                  โครงการ
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('room') }}"
                              class="nav-link {{ request()->routeIs('room') ? 'active' : '' }} {{ request()->routeIs('room.search') ? 'active' : '' }} {{ request()->routeIs('room.edit') ? 'active' : '' }} {{ request()->routeIs('room.detail') ? 'active' : '' }} {{ request()->routeIs('room.booking') ? 'active' : '' }} {{ request()->routeIs('room.booking') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-home"></i>
                              <p>
                                  ห้อง
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>
                      @if ($isRole->dept == 'Finance' || $isRole->role_type == 'SuperAdmin')
                          <li class="nav-item">
                              <a href="{{ route('room.approve') }}"
                                  class="nav-link {{ request()->routeIs('room.approve') ? 'active' : '' }} {{ request()->routeIs('room.approve.search') ? 'active' : '' }}">
                                  <i class="nav-icon fas fa-house-circle-check"></i>
                                  <p>
                                      ห้องรอการอนุมัติ
                                      {{-- <span class="right badge badge-danger">New</span> ------ --}}
                                  </p>
                              </a>
                          </li>
                      @endif
                      @if ($isRole->role_type != 'Partner')
                          <li class="nav-item">
                              <a href="{{ route('rentral') }}"
                                  class="nav-link {{ request()->routeIs('rentral') ? 'active' : '' }} {{ request()->routeIs('rentral.search') ? 'active' : '' }}">
                                  <i class="nav-icon fas fa-person-shelter"></i>
                                  <p>
                                      ห้องเช่า
                                      {{-- <span class="right badge badge-danger">New</span> --}}
                                  </p>
                              </a>
                          </li>
                      @endif


                      @if ($isRole->role_type != 'Sale')
                          <li class="nav-item">
                              <a href="{{ route('report') }}"
                                  class="nav-link {{ request()->routeIs('report') ? 'active' : '' }} {{ request()->routeIs('report.search.in') ? 'active' : '' }} {{ request()->routeIs('report.search') ? 'active' : '' }} {{ request()->routeIs('report.search.out') ? 'active' : '' }}">
                                  <i class="nav-icon fas fa-bar-chart"></i>
                                  <p>
                                      รีพอร์ต
                                      {{-- <span class="right badge badge-danger">New</span> --}}
                                  </p>
                              </a>
                          </li>
                      @endif
                      @if (
                          $isRole->role_type == 'SuperAdmin' ||
                              $isRole->role_type == 'Admin' ||
                              $isRole->role_type == 'Staff' ||
                              $isRole->role_type == 'Sale' ||
                              $isRole->dept == 'Marketing' ||
                              $isRole->dept == 'Finance')
                          <li class="nav-item">
                              <a href="{{ route('promotion') }}"
                                  class="nav-link {{ request()->routeIs('promotion') ? 'active' : '' }}">
                                  <i class="nav-icon fas fa-percent"></i>
                                  <p>
                                      โปรโมชั่น
                                      {{-- <span class="right badge badge-danger">New</span> --}}
                                  </p>
                              </a>
                          </li>
                      @endif
                      @if ($isRole->role_type == 'Staff')
                      <li class="nav-item">
                        <a href="{{ route('team') }}"
                            class="nav-link {{ request()->routeIs('team') ? 'active' : '' }}">
                            <i class="fa fa-users nav-icon"></i>
                            <p>ทีม</p>
                        </a>
                      </li>
                      @endif
                      @if ($isRole->role_type == 'User')
                          @if ($isRole->dept == 'Marketing')
                              <li class="nav-item">
                                  <a href="#" class="nav-link" target="_blank">
                                      <i class="nav-icon fas fa-book"></i>
                                      <p>
                                          คู่มือใช้งาน
                                          <span class="right badge badge-light">Coming soon</span>
                                      </p>
                                  </a>
                              </li>
                          @elseif ($isRole->dept == 'Finance')
                              <li class="nav-item">
                                  <a href="{{ url('uploads/howto/manual_finance_stock.pdf') }}" class="nav-link"
                                      target="_blank">
                                      <i class="nav-icon fas fa-book"></i>
                                      <p>
                                          คู่มือใช้งาน
                                          {{-- <span class="right badge badge-danger">New</span> --}}
                                      </p>
                                  </a>
                              </li>
                          @elseif ($isRole->dept == 'Audit' || $isRole->dept == 'Legal')
                              <li class="nav-item">
                                  <a href="#" class="nav-link" target="_blank">
                                      <i class="nav-icon fas fa-book"></i>
                                      <p>
                                          คู่มือใช้งาน
                                          <span class="right badge badge-light">Coming soon</span>
                                      </p>
                                  </a>
                              </li>
                          @endif
                        @elseif ($isRole->role_type == 'Admin')
                        <li class="nav-item">
                            <a href="#" class="nav-link" target="_blank">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    คู่มือใช้งาน
                                    <span class="right badge badge-light">Coming soon</span>
                                </p>
                            </a>
                        </li>
                        @elseif ($isRole->role_type == 'Staff')
                        <li class="nav-item">
                            <a href="#" class="nav-link" target="_blank">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    คู่มือใช้งาน
                                    <span class="right badge badge-light">Coming soon</span>
                                </p>
                            </a>
                        </li>
                        @elseif ($isRole->role_type == 'SuperAdmin')
                        <li class="nav-item">
                            <a href="#" class="nav-link" target="_blank">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    คู่มือใช้งาน
                                    <span class="right badge badge-light">Coming soon</span>
                                </p>
                            </a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a href="#" class="nav-link" target="_blank">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    คู่มือใช้งาน
                                    <span class="right badge badge-light">Coming soon</span>
                                </p>
                            </a>
                        </li>
                      @endif

                      {{-- <li class="nav-header" style="background-color: rgba(255, 23, 35, 0.486)">สำหรับผู้ดูแลระบบ</li> --}}
                      @if ($isRole->role_type == 'SuperAdmin')
                          <li
                              class="nav-item {{ request()->routeIs('team') ? 'menu-open' : '' }} {{ request()->routeIs('furniture') ? 'menu-open' : '' }} {{ request()->routeIs('facilities') ? 'menu-open' : '' }} {{ request()->routeIs('user') ? 'menu-open' : '' }}">
                              <a href="#"
                                  class="nav-link {{ request()->routeIs('furniture') ? 'active' : '' }} {{ request()->routeIs('facilities') ? 'active' : '' }} {{ request()->routeIs('user') ? 'active' : '' }} {{ request()->routeIs('facilities') ? 'active' : '' }} {{ request()->routeIs('team') ? 'active' : '' }}">
                                  <i class="nav-icon fas fa-cogs"></i>
                                  <p>
                                      ตั้งค่า
                                      <i class="fas fa-angle-left right"></i>
                                  </p>
                              </a>
                              <ul class="nav nav-treeview">
                                  <li class="nav-item">
                                      <a href="{{ route('furniture') }}"
                                          class="nav-link {{ request()->routeIs('furniture') ? 'active' : '' }}">
                                          <i class="fa fa-chair nav-icon"></i>
                                          <p>เฟอร์นิเจอร์</p>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route('facilities') }}"
                                          class="nav-link {{ request()->routeIs('facilities') ? 'active' : '' }}">
                                          <i class="fa fa-desktop nav-icon"></i>
                                          <p>เครื่องใช้ไฟฟ้า</p>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                    <a href="{{ route('team') }}"
                                        class="nav-link {{ request()->routeIs('team') ? 'active' : '' }}">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>ทีม</p>
                                    </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route('user') }}"
                                          class="nav-link {{ request()->routeIs('user') ? 'active' : '' }}">
                                          <i class="fa fa-user nav-icon"></i>
                                          <p>ผู้ใช้งานระบบ</p>
                                      </a>
                                  </li>

                              </ul>
                          </li>
                      @elseif ($isRole->role_type == 'Admin')
                          <li
                              class="nav-item {{ request()->routeIs('furniture') ? 'menu-open' : '' }} {{ request()->routeIs('facilities') ? 'menu-open' : '' }} {{ request()->routeIs('user') ? 'menu-open' : '' }}">
                              <a href="#"
                                  class="nav-link {{ request()->routeIs('furniture') ? 'active' : '' }} {{ request()->routeIs('facilities') ? 'active' : '' }} {{ request()->routeIs('user') ? 'active' : '' }}">
                                  <i class="nav-icon fas fa-cogs"></i>
                                  <p>
                                      ตั้งค่า
                                      <i class="fas fa-angle-left right"></i>
                                  </p>
                              </a>
                              <ul class="nav nav-treeview">
                                  <li class="nav-item">
                                      <a href="{{ route('furniture') }}"
                                          class="nav-link {{ request()->routeIs('furniture') ? 'active' : '' }}">
                                          <i class="fa fa-table nav-icon"></i>
                                          <p>เฟอร์นิเจอร์</p>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="{{ route('facilities') }}"
                                          class="nav-link {{ request()->routeIs('facilities') ? 'active' : '' }}">
                                          <i class="fa fa-chart-simple nav-icon"></i>
                                          <p>เครื่องใช้ไฟฟ้า</p>
                                      </a>
                                  </li>


                              </ul>
                          </li>
                      @endif
                      <li class="nav-item">
                          {{-- <a style="background-color: rgba(255, 23, 35, 0.486)" href="{{route('logoutUser')}}" class="nav-link {{ request()->routeIs('logoutUser') ? 'active' : '' }}"> --}}
                          <a style="" href="{{ route('logoutUser') }}"
                              class="nav-link {{ request()->routeIs('logoutUser') ? 'active' : '' }}">
                              <i class="nav-icon fas fa-sign-out"></i>
                              <p>
                                  ออกจากระบบ
                                  {{-- <span class="right badge badge-danger">New</span> --}}
                              </p>
                          </a>
                      </li>

                  </ul>
              </nav>



          @endif




          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
