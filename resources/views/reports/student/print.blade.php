<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            max-width: 21cm;
            margin: 0 auto;
            padding: 2rem;
            background: #f5f5f5;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .logo {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            object-fit: cover;
        }

        .header-text {
            flex: 1;
        }

        .header-text h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #1a1a1a;
        }

        .header-text h2 {
            margin: 0.25rem 0 0;
            font-size: 1rem;
            color: #666;
            font-weight: normal;
        }

        .student-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-field {
            background: #f8f8f8;
            padding: 1rem;
            border-radius: 8px;
        }

        .info-field label {
            display: block;
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .info-field input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
        }

        .grades {
            margin-bottom: 2rem;
        }

        .grades h3, .remarks h3 {
            font-size: 1.25rem;
            margin: 0 0 1rem;
            color: #1a1a1a;
        }

        .grade-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            padding: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .grade-input {
            width: 80px;
            text-align: center;
            padding: 0.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
        }

        .grade-code {
            background: #f8f8f8;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }

        .grade-code h4 {
            margin: 0 0 1rem;
            font-size: 1rem;
            color: #1a1a1a;
        }

        .remarks {
            background: #f8f8f8;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .remarks p {
            margin: 0;
            line-height: 1.5;
            color: #333;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="logo.png" alt="School Logo" class="logo">
        <div class="header-text">
            <h1>Student Progress Report</h1>
            <h2>Teacher A's House of Learning Biñan</h2>
        </div>
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
