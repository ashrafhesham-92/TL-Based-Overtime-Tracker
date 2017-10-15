<?php
/**
 * @var \Phalcon\Mvc\View\Engine\Php $this
 */
?>

<div class="page-header">
    <?php echo $this->getContent(); ?>
    <h1>
        Your Overtime
    </h1>
    <p>
        <?php echo $this->tag->linkTo(array("userovertime/new", "Create user_overtime")) ?>
    </p>
</div>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>date</th>
        <th>Project</th>
        <th>Created At</th>
        <th>Overtime Amount</th>
        <th>Overtime Unit</th>
        <th>Approved</th>
        <th>Approved By</th>
        <th>Approve Date</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    {% for overtime in overtimes %}
        <tr>
            <td>
                <?php echo date('Y-m-d', $overtime->date); ?>
            </td>
            <td>{{ overtime.project_name }}</td>
            <td>
                <?php echo date('Y-m-d', $overtime->created_at); ?>
            </td>
            <td>{{ overtime.overtime_amount }}</td>
            <td>{{ overtime.unit.name }}</td>
            <td>
                {% if overtime.approved == 1 %}
                <i class="fa fa-check"></i>
                {% else %}
                <i class="fa fa-close"></i>
                {% endif %}
            </td>
            <td>{{ overtime.approvedBy }}</td>
            <td>
                {% if overtime.approve_date !== null %}
                <?php echo date('Y-m-d', $overtime->approve_date); ?>
                {% endif %}
            </td>
            <td>
                <a href="{{ url('userovertime/delete/'~overtime.id) }}">
                    <li class="fa fa-eraser"> &nbsp; Delete</li>
                </a>|
                <a href="{{ url('userovertime/edit/'~overtime.id) }}">
                    <li class="fa fa-pencil"> &nbsp; edit</li>
                </a>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

