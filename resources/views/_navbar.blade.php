<!-- Navigation Bar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('home') }}">XByte Finance</a>
        </div>
        <div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="{{ route('home') }}">Home</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">New
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
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Accounts
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach($nav_accounts as $nav_account)
                                <li><a href="{{ route('accounts.show', $nav_account['id']) }}">{{ $nav_account['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                <li><a href="{{ route('transactions.index') }}">Transactions</a></li>
                <li><a href="{{ route('transfers.index') }}">Transfers</a></li>
                <li><a href="{{ route('payees.index') }}">Payees</a></li>
                <li><a href="{{ route('categories.index') }}">Categories</a></li>
                <li><a href="{{ route('logout') }}"><strong>Logout</strong></a></li>
            </ul>
        </div>
    </div>
</nav><hr/>