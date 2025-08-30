<table>
    <tr>
        <th colspan="2" style="font-size:16px;">Activity Register Export</th>
    </tr>
    <tr><td>Region</td><td>{{ $record->region }}</td></tr>
    <tr><td>Venue</td><td>{{ $record->venue }}</td></tr>
    <tr><td>Activity Date</td><td>{{ $record->activity_date->format('Y-m-d') }}</td></tr>
    <tr><td>Start Time</td><td>{{ $record->start_time }}</td></tr>
    <tr><td>Finish Time</td><td>{{ $record->finish_time }}</td></tr>
    <tr><td>Data Collector</td><td>{{ $record->data_collector }}</td></tr>
    <tr><td>Collection Date</td><td>{{ optional($record->collection_date)->format('Y-m-d') }}</td></tr>
    <tr><td>Verified By</td><td>{{ $record->verified_by }}</td></tr>
    <tr><td>Verification Date</td><td>{{ optional($record->verification_date)->format('Y-m-d') }}</td></tr>
</table>

<br><br>

<table>
    <tr>
        <th>Name</th>
        <th>Institution</th>
        <th>Designation</th>
        <th>Contact</th>
        <th>Email</th>
    </tr>
    @foreach($record->internal_attendance_entries as $entry)
    <tr>
        <td>{{ $entry->name }}</td>
        <td>{{ $entry->institution }}</td>
        <td>{{ $entry->designation }}</td>
        <td>{{ $entry->contact }}</td>
        <td>{{ $entry->email }}</td>
    </tr>
    @endforeach
</table>
