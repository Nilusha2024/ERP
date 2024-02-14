{{-- filter these menus --}}

<li class="nav-item">

    @php

        $user_role = Auth::user()->role_id;

        $permissions_for_general = [1];
        $permissions_for_user = [1, 2, 4];
        $permissions_for_location = [1, 2];
        $permissions_for_stock_report = [1, 2, 5, 9, 4, 3, 12, 13, 14];
        $permissions_for_stock_ledger = [1, 2, 4, 13];
        $permissions_for_serial_report = [1, 2, 4, 9, 12, 13];
        $permissions_for_movement_report = [1, 2, 4, 9, 12, 13];
        $permissions_for_location_movement_report = [1, 2, 4, 12];
        $permissions_serial_movement_report = [1, 2, 4, 12];
        $permissions_for_MR = [1, 2, 3, 4, 9, 5, 12];
        $permissions_for_TO = [1, 2, 9, 4, 5, 12];
        $permissions_for_PO = [1, 2, 4, 5, 6];
        $permissions_for_GRN = [1, 2, 4, 5];
        $permissions_for_serial_edit = [1, 2, 4];
        $permissions_for_item = [1, 2, 4];
        $permissions_for_stock_adjustment = [1, 2, 4];
        $permissions_for_item_return = [1, 2, 3, 9, 4, 12];
        $permissions_for_price_card = [1, 2, 4, 5];
        $permissions_for_MR_stock = [1, 2, 3, 9, 12];
        $permissions_for_FIX_stock = [1, 4, 9, 12];
        $permissions_for_mr_pending_location = [13];
        $permissions_for_checkzone = [14];
        
    @endphp

    {{-- Home --}}
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>

    {{-- General --}}
    @if (in_array($user_role, $permissions_for_general))
        <a href="{{ route('general') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>General</p>
        </a>
    @endif

    {{-- User --}}
    @if (in_array($user_role, $permissions_for_user))
        <a href="{{ route('user') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>User</p>
        </a>
    @endif

    {{-- Item --}}
    @if (in_array($user_role, $permissions_for_item))
        <a href="{{ route('item') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Item</p>
        </a>
    @endif

    {{-- Location --}}
    @if (in_array($user_role, $permissions_for_location))
        <a href="{{ route('location') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>WareHouse/Location</p>
        </a>
    @endif

    {{-- MR --}}
    @if (in_array($user_role, $permissions_for_MR))
        <a href="mr" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Material Request</p>
        </a>
        <a href="{{ route('mr_tr_view') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>MR Received Note</p>
        </a>
    @endif

    {{-- MR Stock --}}
    @if (in_array($user_role, $permissions_for_MR_stock))
        <a href="{{ route('MR_item_stock') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Material Stock</p>
        </a>
    @endif

    {{-- Fix Stock --}}
    @if (in_array($user_role, $permissions_for_FIX_stock))
        <a href="{{ route('fix_item_stock1') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Fix Item Stock</p>
        </a>
        
    @endif

    {{-- PO --}}
    @if (in_array($user_role, $permissions_for_PO))
        <a href="{{ route('po') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Purchase Order</p>
        </a>
    @endif

    {{-- Price Card --}}
    @if (in_array($user_role, $permissions_for_price_card))
        <a href="{{ route('price_card') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Price Card</p>
        </a>
    @endif

    {{-- GRN --}}
    @if (in_array($user_role, $permissions_for_GRN))
        <a href="{{ route('grn') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Good Received Note</p>
        </a>
    @endif


    {{-- Movement Report --}}
    @if (in_array($user_role, $permissions_for_serial_edit))
        <a href="{{ route('serailedit') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Serial Edit</p>
        </a>
    @endif


    {{-- TO (Transfer Order) --}}
    @if (in_array($user_role, $permissions_for_TO))
        <a href="{{ route('transfer_order') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Transfer Order</p>
        </a>
    @endif

    {{-- Item Return --}}
    @if (in_array($user_role, $permissions_for_item_return))
        <a href="{{ route('itemeretur') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Item Return</p>
        </a>
    @endif

    {{-- Stock Adjustment --}}
    @if (in_array($user_role, $permissions_for_stock_adjustment))
        <a href="{{ route('stockadjestment') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Stock Adjustment</p>
        </a>
    @endif

    {{-- Stock Report --}}
    @if (in_array($user_role, $permissions_for_stock_report))
        <a href="{{ route('stock') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Stock Report</p>
        </a>
    @endif

    {{-- Serial Report --}}
    @if (in_array($user_role, $permissions_for_serial_report))
        <a href="{{ route('serial') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Item Serial No. Report</p>
        </a>
    @endif

    {{-- Movement Report --}}
    @if (in_array($user_role, $permissions_for_movement_report))
        <a href="{{ route('itemMovement') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Item Movement Location Wise Report</p>
        </a>
    @endif


    {{-- Movement Report --}}
    @if (in_array($user_role, $permissions_for_location_movement_report))
        <a href="{{ route('itemMovementLocationWise') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Item Movement Report</p>
        </a>
    @endif

    @if (in_array($user_role, $permissions_serial_movement_report))
        <a href="{{ route('serialnohistory') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Serial Movement Report</p>
        </a>
    @endif

    {{-- Movement Report --}}
    @if (in_array($user_role, $permissions_for_mr_pending_location))
        <a href="{{ route('operationreport') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Pending MR Received Location</p>
        </a>
    @endif
    {{-- Stock Ledger --}}
    {{-- Stock (Transfer) Ledger --}}
    @if (in_array($user_role, $permissions_for_stock_ledger))
        <a href="{{ route('stock_transfer_ledger') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Stock Ledger</p>
        </a>
    @endif
        {{-- zone check --}}
        @if (in_array($user_role, $permissions_for_checkzone))
        <a href="{{ route('checkzone.form') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Zone Check</p>
        </a>
    @endif
</li>
