<div>

      <table class="table">
            <thead>
                  <tr>
                        <th class="text-center">User ID di Deli yg tidak terdapat di database excel</th>
                  </tr>
            </thead>
            <tbody>
                  @foreach ($array as $arr )
                  <tr>
                        <td class="text-center">{{ $arr['Karyawan_id'] }}</td>
                  </tr>

                  @endforeach
            </tbody>
      </table>

</div>
