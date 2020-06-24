<?php include 'views/partials/header.php'; ?>

<div id='task'>
    <p>{{ message }}</p>
    <form>
        <div><p>Тема:</p></div>
        <div><input v-model:value="task.title"></div>

        <div><p>Тип</p></div>
        <div>
            <select v-model:value="task.type">
                <option v-for="type in types" :key="type">{{ type }}</option>
            </select>
        </div>

        <div><p>Место:</p></div>
        <div><input v-model:value="task.place"></div>

        <div><p>Дата и время:</p></div>
        <div><input type='datetime-local' v-model:value='task.time'></div>

        <div><p>Длительность</p></div>
        <div>
            <select v-model:value="task.length">
                <option v-for="length in lengthes" :key="length">{{ length }}</option>
            </select>
        </div>

        <div><p>Комментарий</p></div>
        <div><input v-model:value="task.comment"></div>

        <div></div>
        <div><input type='button' value="Сохранить" @click="put"></div>
    </form>
</div>

<script>

let form = new Vue({
    el: '#task',
    data: {
        message: '',
        task: <?= json_encode($task) ?>,
        types: <?= json_encode(TaskController::$types) ?>,
        lengthes: <?= json_encode(TaskController::$lengthes) ?>,
    },
    mounted: function () {
        if (this.task.time) {
            this.task.time = this.task.time.replace(' ', 'T');
        } else {
            let date = new Date;
            this.task.time = date.toISOString().substr(0, 16);
        }
    },
    methods: {
        put: async function(event) {
            let task = Object.assign({}, this.task);
            task.time = task.time.replace('T', ' ');
            task = JSON.stringify(task);
            let response = await fetch('/tasks.php', {method: 'put', body: task, headers: {'Content-Type': 'application/json'}});
            let text = await response.text();
            console.log(text);
            if (response.ok) {
                window.location.href="/tasks.php?list";
            } else {
                let json = JSON.parse(text);
                this.message = json["message"];
            }
        }
    }

})

</script>

<?php include 'views/partials/footer.php'; ?>
