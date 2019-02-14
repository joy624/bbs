<template>
    <div class="container border">
        <form>
            <div class="form-group">
                <label for="FormControlEmail">请输入邮箱：</label>
                <input type="text" v-model="email" class="form-control-file" id="FormControlEmail">
            </div>
            <input type="button" class="btn btn-primary" value="下一步" @click="updateEmail">
        </form>
    </div>
</template>
<script>
    export default {
        name: "findPwd",
        data() {
            return {
            };
        },
        methods: {}
    }
</script>
<style scoped>

</style>