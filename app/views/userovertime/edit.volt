<?php
/**
 * @var \Phalcon\Mvc\View\Engine\Php $this
 */
?>

<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous"><?php echo $this->tag->linkTo(array("user_overtime", "Back")) ?></li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Edit your overtime
    </h1>
</div>
<form class="form-horizontal" method="post" action="{{ url('user_overtime/save') }}" autocomplete="off" enctype="multipart/form-data">
    <div class="form-group">
        <label for="fieldDate" class="col-sm-2 control-label">Date</label>
        <div class="col-sm-10">
            <?php echo $this->tag->dateField(array("date", "size" => 30, "class" => "form-control", "id" => "fieldDate")) ?>
        </div>
    </div>

    <div class="form-group">
        <label for="fieldOvertimeAmount" class="col-sm-2 control-label">Overtime Amount</label>
        <div class="col-sm-10">
            <?php echo $this->tag->textField(array("overtime_amount", "type" => "number", "class" => "form-control", "id" => "fieldOvertimeAmount")) ?>
        </div>
    </div>

    <div class="form-group">
        <label for="fieldOvertimeUnitId" class="col-sm-2 control-label">Overtime Unit</label>
        <div class="col-sm-10">
            <select name="overtime_unit_id" id="fieldOvertimeUnitId" class="form-control">
                {% for unit in units %}
                    {% if unit.id == defaultUnit %}
                        <option selected value="{{ unit.id }}">{{ unit.name }}</option>
                    {% else %}
                        <option value="{{ unit.id }}">{{ unit.name }}</option>
                    {% endif %}
                {% endfor %}
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="fieldProjectName" class="col-sm-2 control-label">Name Of Project</label>
        <div class="col-sm-10">
            <?php echo $this->tag->textField(array("project_name", "type" => "text", "class" => "form-control", "id" => "fieldProjectName")) ?>
            <!--<input value="{{ project_name }}" type="text" name="project_name" id="fieldProjectName" class="form-control" required size="30">-->
        </div>
    </div>

    <div class="form-group">
        <label for="fieldProjectName" class="col-sm-2 control-label">Details</label>
        <div class="col-sm-10">
            <?php echo $this->tag->textArea(["details", "cols"=>30, "rows"=>5, "class"=>"form-control", "required"=>"required"]) ?>
            {#<textarea required name="details" id="details" class="form-control" cols="30" rows="5">{{ details }}</textarea>#}
        </div>
    </div>

    <input type="hidden" name="id" value="{{ id }}">

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php echo $this->tag->submitButton(array("Save", "class" => "btn btn-default")) ?>
        </div>
    </div>
</form>