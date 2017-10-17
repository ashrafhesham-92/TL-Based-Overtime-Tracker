<?php
/**
 * @var \Phalcon\Mvc\View\Engine\Php $this
 */
?>

<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous"><?php echo $this->tag->linkTo(array("userovertime", "Go Back")) ?></li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Create new overtime
    </h1>
</div>

<?php
    echo $this->tag->form(
        array(
            "userovertime/create",
            "autocomplete" => "off",
            "class" => "form-horizontal"
        )
    );
?>

<div class="form-group">
    <label for="fieldDate" class="col-sm-2 control-label">Date</label>
    <div class="col-sm-10">
        <input type="date" name="date" id="fieldDate" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="fieldOvertimeAmount" class="col-sm-2 control-label">Amount Of Overtime</label>
    <div class="col-sm-10">
        <input type="number" name="overtime_amount" id="fieldOvertimeAmount" class="form-control" min="1" max="24">
    </div>
</div>

<div class="form-group">
    <label for="fieldOvertimeUnitId" class="col-sm-2 control-label">Unit Of Overtime</label>
    <div class="col-sm-10">
        <select name="overtime_unit_id" id="fieldOvertimeUnitId" class="form-control">
            <option value="0" disabled>---Enter Unit---</option>
            {% for unit in units %}
                <option value="{{ unit.id }}">{{ unit.name }}</option>
            {% endfor %}
        </select>
    </div>
</div>

<div class="form-group">
    <label for="fieldProjectName" class="col-sm-2 control-label">Name Of Project</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(array("project_name", "size" => 30, "class" => "form-control", "id" => "fieldProjectName")) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?php echo $this->tag->submitButton(array("Save", "class" => "btn btn-default")) ?>
    </div>
</div>

<?php echo $this->tag->endForm(); ?>
