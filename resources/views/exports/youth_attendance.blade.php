<table>
    <tr>
        <th colspan="2" style="font-size:16px;">Youth Activity Register Export</th>
    </tr>
    <tr><td>Region</td><td>{{ $record->region->name }}</td></tr>
    <tr><td>Venue</td><td>{{ $record->venue }}</td></tr>
    <tr><td>Activity Type</td><td>{{ $record->activity_type }}</td></tr>
    <tr><td>Topics Covered</td><td>{{ is_array($record->topics_covered) ? implode(', ', $record->topics_covered) : $record->topics_covered }}</td></tr>
    <tr><td>Date</td><td>{{ $record->activity_date->format('Y-m-d') }}</td></tr>
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
        <th>Surname</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Employment Status</th>
        <th>Employment Type</th>
        <th>Education Level</th>
        <th>Institution</th>
        <th>Contact</th>
        <th>Email</th>
    </tr>
    @foreach($record->youth_attendance_entries as $entry)
    <tr>
        <td>{{ $entry->name }}</td>
        <td>{{ $entry->surname }}</td>
        <td>{{ $entry->age }}</td>
        <td>{{ $entry->gender }}</td>
        <td>{{ $entry->employment_status ? 'Yes' : 'No' }}</td>
        <td>{{ $entry->employment_type }}</td>
        <td>{{ $entry->education_level }}</td>
        <td>{{ $entry->institution }}</td>
        <td>{{ $entry->contact }}</td>
        <td>{{ $entry->email }}</td>
    </tr>
    @endforeach
</table>
