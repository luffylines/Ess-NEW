
<x-app-layout>
    <div class="container">
        <h1>My Payslips</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Amount</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payslips as $payslip)
                    <tr>
                        <td>{{ $payslip['month'] }}</td>
                        <td>${{ number_format($payslip['amount'], 2) }}</td>
                        <td><a href="{{ $payslip['download_link'] }}" class="btn btn-primary btn-sm">Download</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
