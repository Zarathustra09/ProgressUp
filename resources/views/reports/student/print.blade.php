<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #F87B5E;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 10px;
        }

        .student-info {
            background-color: #FFFBE6;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-field {
            margin-bottom: 10px;
        }

        .info-field label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        .info-field input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .grades {
            background-color: #E5F7F7;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .grade-item {
            display: grid;
            grid-template-columns: 2fr 1fr;
            margin-bottom: 10px;
            align-items: center;
        }

        .grade-code {
            background-color: #FFFFFF;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .remarks {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .grade-input {
            width: 60px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="logo.png" alt="School Logo" class="logo">
    <h1>Student Progress Report</h1>
    <h2>Teacher A's House of Learning Biñan</h2>
</div>

<div class="student-info">
    <div class="info-grid">
        <div class="info-field">
            <label>Student's name</label>
            <input type="text" value="Alonzo Primo Alonte">
        </div>
        <div class="info-field">
            <label>Teacher's name</label>
            <input type="text" value="Tr. Tin">
        </div>
        <div class="info-field">
            <label>Program/s</label>
            <input type="text" value="BM | PBL | SUM">
        </div>
        <div class="info-field">
            <label>Age</label>
            <input type="text" value="2 y.o.">
        </div>
    </div>
</div>

<div class="grades">
    <h3>Grades</h3>
    <div class="grade-item">
        <span>Can follow simple instructions</span>
        <input type="text" class="grade-input" value="S">
    </div>
    <div class="grade-item">
        <span>Has a longer attention span</span>
        <input type="text" class="grade-input" value="S">
    </div>
    <div class="grade-item">
        <span>Listening</span>
        <input type="text" class="grade-input" value="S">
    </div>
    <div class="grade-item">
        <span>Can pronounce some letter of the alphabet</span>
        <input type="text" class="grade-input" value="S">
    </div>
    <div class="grade-item">
        <span>Can say his/her name</span>
        <input type="text" class="grade-input" value="NI">
    </div>
    <div class="grade-item">
        <span>Can answer simple questions correctly</span>
        <input type="text" class="grade-input" value="S">
    </div>
    <div class="grade-item">
        <span>Can cooperate well with teachers</span>
        <input type="text" class="grade-input" value="S">
    </div>
    <div class="grade-item">
        <span>Shows generosity towards other people</span>
        <input type="text" class="grade-input" value="S">
    </div>

    <div class="grade-code">
        <h4>Grade Code</h4>
        <div class="grade-item">
            <span>O</span>
            <span>Outstanding</span>
        </div>
        <div class="grade-item">
            <span>VS</span>
            <span>Very Satisfactory</span>
        </div>
        <div class="grade-item">
            <span>S</span>
            <span>Satisfactory</span>
        </div>
        <div class="grade-item">
            <span>NI</span>
            <span>Needs Improvement</span>
        </div>
    </div>
</div>

<div class="remarks">
    <h3>Remarks</h3>
    <p>At first, Primo had a hard time staying in the sessions. He would cry every time his parent left. But now, he is very cheerful and no longer acts the way he did before. When he first started, he wasn't familiar with basic concepts like colors, shapes, animals, and letters. Now he knows them well and can demonstrate what he has learned, especially when working on worksheets.</p>
    <p>He can sit longer to finish his worksheets, but it's important to choose activities that interest him so he stays focused. He still gets distracted sometimes, but it's much better than when he first started. Overall, his basic vocabulary has grown, his behavior has improved a lot, and while his speech still needs more attention since he only says a few words, he is making progress. Good job, Primo! Keep it up!</p>
</div>
</body>
</html>
