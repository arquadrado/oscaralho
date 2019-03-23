<table>
    <thead>
      <tr>
        <th></th>
        @foreach($table['header'] as $year => $months)    
          <th colspan="{{ sizeof($months) * 3 }}">{{ $year}}</th>
        @endforeach
      </tr>
      <tr>
        <th></th>
        @foreach($table['header'] as $year => $months)    
          @foreach($months as $month)    
          <th colspan="3">{{ $month }}</th>
          @endforeach
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($table['body'] as $category => $years)    
        <tr>
          <td>{{ $category }}</td>
            @foreach($years as $year)    
              @foreach($year as $bound)    
              <td>{{ $bound->display_bound }}</td>
              <td>{{ $bound->expenses_sum }}</td>
              <td>{{ $bound->ratio }}</td>
              @endforeach
            @endforeach
        </tr>
      @endforeach
    </tbody>
</table>

