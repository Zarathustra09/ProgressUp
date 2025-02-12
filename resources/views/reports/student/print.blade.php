<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 0 auto;
            padding: 15px;
        }

        .container {
            background: white;
            border-radius: 8px;
            padding: 15px;
        }

        .header {
            background-color: #F87B5E;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            color: white;
            margin-bottom: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            border-radius: 25px;
            margin: 0 auto 8px;
        }

        .header h1 { font-size: 1.3rem; margin: 0; }
        .header h2 { font-size: 1rem; margin: 5px 0 0; }

        .student-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
            background-color: #FFFBE6;
            padding: 12px;
            border-radius: 8px;
        }

        .info-field {
            margin-bottom: 8px;
        }

        .info-field label {
            display: block;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 3px;
        }

        .info-field input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .grades {
            background-color: #FFFBE6;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .grades h3, .remarks h3 {
            font-size: 1rem;
            margin: 0 0 8px;
        }

        .grade-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .grade-input {
            width: 50px;
            text-align: center;
            padding: 3px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .grade-code {
            background: white;
            padding: 10px;
            border-radius: 6px;
            margin-top: 12px;
            font-size: 0.85rem;
        }

        .grade-code h4 {
            margin: 0 0 8px;
            font-size: 0.9rem;
        }

        .remarks {
            background-color: #FFFBE6;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .remarks p { margin: 0; }

        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="schoollogo.png" alt="School Logo" class="logo">
        <h1>Student Progress Report</h1>
        <h2>Teacher A's House of Learning Biñan</h2>
    </div>

    <div class="student-info">
        <div class="info-field">
            <label>Student's name</label>
            <input type="text" value="{{ $reportData['student']['first_name'] }} {{ $reportData['student']['last_name'] }}">
        </div>
        <div class="info-field">
            <label>Teacher's name</label>
            <input type="text" value="{{ $reportData['teacher_name'] }}">
        </div>
        <div class="info-field">
            <label>Program/s</label>
            <input type="text" value="{{ implode(' | ', array_column($reportData['schedules'], 'event_name')) }}">
        </div>
        <div class="info-field">
            <label>Age</label>
            <input type="text" value="{{ $reportData['student']['age'] }}">
        </div>
    </div>

    <div class="grades">
        <h3>Grades</h3>
        @foreach($reportData['grades'] as $scheduleId => $criteria)
            @foreach($criteria as $key => $value)
                @if(strpos($key, 'criterion') !== false && strpos($key, 'Grade') === false)
                    <div class="grade-item">
                        <span>{{ $value }}</span>
                        <input type="text" class="grade-input" value="{{ $criteria[$key . 'Grade'] }}">
                    </div>
                @endif
            @endforeach
        @endforeach

        <div class="grade-code">
            <h4>Grade Code</h4>
            @foreach(config('grade') as $code => $description)
                <div class="grade-item">
                    <span>{{ $code }}</span>
                    <span>{{ $description }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="remarks">
        <h3>Remarks</h3>
        <p>{{ $reportData['remarks'] }}</p>
    </div>
</div>
</body>
</html>
