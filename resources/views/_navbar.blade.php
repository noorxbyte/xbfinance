<!-- Navigation Bar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span> XByte Finance</a>
        </div>
        <div>
            <ul class="nav navbar-nav">
                <li class="{{ (Request::segment(1) == 'accounts' && empty(Request::segment(2))) ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                <li class="dropdown dropdown-hover {{ Request::segment(2) == 'create' ? 'active' : '' }}">
                    <a id="unclickable" class="dropdown-toggle" data-toggle="dropdown" href="#">New
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('transactions.create') }}">Transaction</a></li>
                        <li><a href="{{ route('transfers.create') }}">Transfer</a></li>
                        <li><a href="{{ route('accounts.create') }}">Account</a></li>
                        <li><a href="{{ route('payees.create') }}">Payee</a></li>
                        <li><a href="{{ route('categories.create') }}">Cetegory</a></li>
                    </ul>
                </li>
                @if(isset($nav_accounts))
                    <li class="dropdown dropdown-hover {{ (Request::segment(1) == 'accounts' && !empty(Request::segment(2)) && Request::segment(2) != 'create') ? 'active' : '' }}">
                        <a id="accounts" class="dropdown-toggle" data-toggle="dropdown" href="{{ route('accounts.index') }}">Accounts
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach($nav_accounts as $nav_account)
                                <li><a href="{{ route('accounts.show', $nav_account['id']) }}">{{ $nav_account['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                <li class="{{ Request::segment(1) == 'transactions' ? 'active' : '' }}"><a href="{{ route('transactions.index') }}">Transactions</a></li>
                <li class="{{ Request::segment(1) == 'transfers' ? 'active' : '' }}"><a href="{{ route('transfers.index') }}">Transfers</a></li>
                <li class="{{ Request::segment(1) == 'payees' ? 'active' : '' }}"><a href="{{ route('payees.index') }}">Payees</a></li>
                <li class="{{ Request::segment(1) == 'categories' ? 'active' : '' }}"><a href="{{ route('categories.index') }}">Categories</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    <li class="dropdown dropdown-hover">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> {{ $username }}
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="validateSubmit"><span class="glyphicon glyphicon-check"></span> Validator</a></li>
                            <li><a href="{{ route('user.settings') }}"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                            <li><a href="{{ route('logout') }}"><span class="glyphicon glyphicon-log-out"></span> <strong>Logout</strong></a></li>
                        </ul>
                    </li>
                @else
                    <li><a href="{{ route('register') }}"><span class="glyphicon glyphicon-user"></span> Register</a></li>
                    <li><a href="{{ route('login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                @endif
            </ul>
            </ul>
        </div>
    </div>
</nav><hr/>