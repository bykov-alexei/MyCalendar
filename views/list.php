<?php include 'views/partials/header.php' ?>

<div id='table'>
    <div class='nav'>
        <select v-on:change="getTasks" v-model='parameters.option'>
            <option value='any'>Все задачи</option>
            <option value='0'>Невыполненные задачи</option>
            <option value='1'>Выполненные задачи</option>
        </select>
        <span>С: <input v-on:change="getTasks" type='date' v-model="parameters.from"></span>
        <span>По: <input v-on:change="getTasks" type='date' v-model="parameters.to"></span>
        <a v-on:click="today">сегодня</a>
        <a v-on:click="tomorrow">завтра</a>
        <a v-on:click="week">на эту неделю</a>
        <a v-on:click="nextWeek">на след. неделю</a>
    </div>
    <table>
        <tr>
            <th></th>
            <th>Тип</th>
            <th>Задача</th>
            <th>Место</th>
            <th>Дата и время</th>
        </tr>
        <tr is='task' v-on:update="getTasks" v-for="task of tasks" v-bind:task='task'>
    </table>
</div>

<script>
window.onload = function() {
    let task = {
        props: ['task'],
        template: ` <tr @click='click'>
                        <td><input type="checkbox" @click="put" v-model="task.finished"></td>
                        <td>{{ task.type }}</td>
                        <td>
                          <a v-bind:href="\'/tasks.php?edit&id=\' + task.id">{{ task.title }}</a>
                          <button v-on:click="erase()">Удалить</button>
                        </td>
                        <td>{{ task.place }}</td>
                        <td>{{ task.time }}</td>
                    </tr>`,
        data: function () {
            return {
                task: Object(),
            }
        },
        mounted: function() {
            this.task.finished = Number(this.task.finished);
        },
        methods: {
            erase: async function(event) {
                let response = await fetch('/tasks.php', {method: 'delete', body: JSON.stringify(this.task), headers: {'Content-Type': 'application/json'}});
                let text = await response.text();
                console.log(response.status + ' delete ' + text);
                if (response.ok) {
                    this.$emit('update');
                }
            },
            put: async function(event) {
                let task = Object.assign({}, this.task);
                let response = await fetch('/tasks.php', {method: 'put', body: JSON.stringify(task), headers: {'Content-Type': 'application/json'}});
                let text = await response.text();
                console.log(response.status + ' finish ' + text);
            },
            click: function () {
                console.log(this.task);
            }
        }
    };

    let table = new Vue({
        el: '#table',
        data: {
            tasks: [],
            parameters: {
                from: "<?= date('Y-m-d') ?>",
                to: "<?= date('Y-m-d') ?>",
                option: 'any',
            },
        },
        components: {'task' :task},
        beforeMount: async function () {
            this.getTasks();
        },
        methods: {
            today: async function(event) {
                let today = new Date();
                today = today.toISOString().substr(0, 10);
                this.parameters.from = today;
                this.parameters.to = today;
                this.getTasks();
            },
            tomorrow: async function(event) {
                let date = new Date();
                date.setDate(date.getDate() + 1);
                date = date.toISOString().substr(0, 10);
                this.parameters.from = date;
                this.parameters.to = date;
                this.getTasks();
            },
            week: async function(event) {
                let date = new Date;
                let start = new Date(date.setDate(date.getDate() - date.getDay() + 1));
                start = start.toISOString().substr(0, 10);
                let end = new Date(date.setDate(date.getDate() - date.getDay()+7));
                end = end.toISOString().substr(0, 10);
                this.parameters.from = start;
                this.parameters.to = end;
                this.getTasks();
            },
            nextWeek: async function(event) {
                let date = new Date;
                let start = new Date(date.setDate(date.getDate() - date.getDay() + 8));
                start = start.toISOString().substr(0, 10);
                let end = new Date(date.setDate(date.getDate() - date.getDay()+7));
                end = end.toISOString().substr(0, 10);
                this.parameters.from = start;
                this.parameters.to = end;     
                this.getTasks();
            },
            getTasks: async function(event) {
                for (let task of this.tasks) {
                    delete task;
                }
                params = String(new URLSearchParams(this.parameters));
                let response = await fetch('/tasks.php?' + params);
                let text = await response.text();
                console.log(response.status + ' get_tasks ' + text);
                if (response.ok) {
                    this.tasks = JSON.parse(text);
                }
            }
        }
    })
}
</script>

<?php include 'views/partials/footer.php' ?>