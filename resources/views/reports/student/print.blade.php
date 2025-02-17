<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 0 auto;
            padding: 10px;
        }

        .container {
            background: white;
            border-radius: 6px;
            padding: 10px;
        }

        .header {
            background-color: #F87B5E;
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            color: white;
            margin-bottom: 10px;
        }

        .logo {
            width: 40px;
            height: 40px;
            border-radius: 20px;
            margin: 0 auto 4px;
        }

        .header h1 { font-size: 1.1rem; margin: 0; }
        .header h2 { font-size: 0.9rem; margin: 3px 0 0; }

        .student-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 10px;
            background-color: #FFFBE6;
            padding: 8px;
            border-radius: 6px;
        }

        .info-field {
            margin-bottom: 6px;
        }

        .info-field label {
            font-size: 0.75rem;
            color: #666;
            margin-bottom: 2px;
        }

        .info-field input {
            padding: 3px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 0.85rem;
        }

        .activities {
            background-color: #FFFBE6;
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .activities h3, .remarks h3 {
            font-size: 0.9rem;
            margin: 0 0 8px;
            color: #444;
        }

        .activity-item {
            background: white;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .activity-key {
            font-weight: 600;
            color: #F87B5E;
            font-size: 0.9rem;
            margin-bottom: 4px;
            display: block;
        }

        .activity-description {
            color: #555;
            font-size: 0.8rem;
            padding-left: 12px;
            position: relative;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .activity-description:before {
            content: "";
            position: absolute;
            left: 0;
            top: 6px;
            width: 4px;
            height: 4px;
            background: #F87B5E;
            border-radius: 50%;
        }

        .remarks {
            background-color: #FFFBE6;
            padding: 8px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .remarks p { margin: 0; }


        .student-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 10px;
            background-color: #FFFBE6;
            padding: 8px;
            border-radius: 6px;
        }

        .info-field {
            margin-bottom: 6px;
        }

        .info-field.full-width {
            grid-column: span 2;
        }

        .info-field label {
            display: block;
            font-size: 0.75rem;
            color: #666;
            margin-bottom: 2px;
        }

        .info-field input {
            width: 100%;
            padding: 3px;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 0.85rem;
            background-color: white;
        }

        @media print {
            body { padding: 0; }
            @page { margin: 0.5cm; }
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
        <div class="info-field full-width">
            <label>Student's name</label>
            <input type="text" value="{{ $reportData['student']['first_name'] }} {{ $reportData['student']['last_name'] }}">
        </div>
        <div class="info-field">
            <label>Age</label>
            <input type="text" value="{{ $reportData['student']['age'] }}">
        </div>
        <div class="info-field full-width">
            <label>Teacher's name</label>
            <input type="text" value="{{ $reportData['teacher_name'] }}">
        </div>
        <div class="info-field">
            <label>Overall Grade</label>
            <input type="text" value="{{ $reportData['overall_grade'] }}">
        </div>
        <div class="info-field full-width">
            <label>Program</label>
            <input type="text" value="{{ $reportData['schedules'][0]['event_name'] }}">
        </div>
    </div>

    <div class="activities">
        <h3>Activities</h3>
        @foreach($reportData['activities'] as $scheduleId => $activitySet)
            @foreach($activitySet as $activity)
                <div class="activity-item">
                    <span class="activity-key">{{ $activity['key'] }}</span>
                    @foreach($activity['descriptions'] as $description)
                        <div class="activity-description">{{ $description }}</div>
                    @endforeach
                </div>
            @endforeach
        @endforeach
    </div>

    <div class="remarks">
        <h3>Remarks</h3>
        <p>{{ $reportData['remarks'] }}</p>
    </div>
</div>
</body>
</html>
