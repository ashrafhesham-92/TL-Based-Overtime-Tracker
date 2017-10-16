<?php
/**
 * @var \Phalcon\Mvc\View\Engine\Php $this
 */
?>

<div class="page-header">
    <?php echo $this->getContent(); ?>
    <h1>
        {% if session.get('user').rule_id === teamLeaderRule %}
            {% if teamMember is defined %}
                {{ teamMember.name }}'s Overtime Records
            {% else %}
                Team member overtime records
            {% endif %}
        {% elseif session.get('user').rule_id === memberRule %}
            Your Overtime
        {% endif %}
    </h1>
</div>
<table class="table table-bordered" style="text-align: center;">
    <thead>
    <tr>
        <th>date</th>
        <th>Project</th>
        <th>Created At</th>
        <th>Overtime Amount</th>
        <th>Overtime Unit</th>
        <th>Approved</th>
        <th>Approved/Rejected By</th>
        <th>Approve/Reject Date</th>
        <th>Actions</th>
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
                <?php echo date('Y-m-d H:i', $overtime->created_at); ?>
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
            <td>
                {% if overtime.approvedBy %}
                    {{ overtime.approvedBy.name }}</td>
                {% endif %}
            <td>
                {% if overtime.approve_date !== null %}
                <?php echo date('Y-m-d H:i', $overtime->approve_date); ?>
                {% endif %}
            </td>
            <td>
            {% if session.get('user').rule_id === memberRule %}
                {% if overtime.approved != 1 %}
                    <a class="btn btn-primary" href="{{ url('userovertime/edit/'~overtime.id) }}">
                        <li title="edit" class="fa fa-pencil"></li>
                    </a>
                    <a class="btn btn-danger" href="{{ url('userovertime/delete/'~overtime.id) }}">
                        <li title="delete" class="fa fa-trash"></li>
                    </a>
                {% else %}
                    <a class="btn btn-danger" href="#">
                        <li class="fa fa-eye-slash"> no actions</li>
                    </a>
                {% endif %}
            {% elseif session.get('user').rule_id === teamLeaderRule %}
                {% if overtime.approved != 1 %}
                    <a class="btn btn-success" href="{{ url('teamleader/approve/'~overtime.id) }}">
                        <li title="approve" class="fa fa-check"></li>
                    </a>
                {% else %}
                    <a class="btn btn-danger" href="{{ url('teamleader/reject/'~overtime.id) }}">
                        <li title="reject" class="fa fa-close"></li>
                    </a>
                {% endif %}
            {% endif %}
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

