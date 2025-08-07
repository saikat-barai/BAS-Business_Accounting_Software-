@extends('backend.master')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalIncome, 2) }} ৳</h3>

                            <p>Total Income</p>
                        </div>
                        <div class="icon">
                            
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($totalExpense, 2) }} ৳</h3>

                            <p>Total Expense</p>
                        </div>
                        <div class="icon">
                            
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h2><span class="{{ $profitOrLoss >= 0 ? 'text-white' : 'text-white' }}">
                                    {{ number_format(abs($profitOrLoss), 2) }} ৳
                                    ({{ $profitOrLoss >= 0 ? 'Profit' : 'Loss' }})
                                </span></h2>

                            <p>Profit/Loss:</p>
                        </div>
                        <div class="icon">
                            
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($bankCashAccounts, 2) }}</h3>

                            <p>Current Balance</p>
                        </div>
                        <div class="icon">
                            
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
