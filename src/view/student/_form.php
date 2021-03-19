<form method="post">

    <select name="Student[board_id]" class="form-control">
        <option value="2">CSM</option>
        <option value="1">CSMB</option>
    </select>            
        
    <div class="form-group">
        <label for="student-name">Name</label> 
        <input 
            id="news-title"
            type="text" class="form-control" 
            name="Student[student_name]" 
            value="<?= htmlspecialchars($student->getData()->student_name ?? '')?>"
            autofocus
        >
    </div>

    <button type="submit" class="btn btn-primary">Save</button>

</form>