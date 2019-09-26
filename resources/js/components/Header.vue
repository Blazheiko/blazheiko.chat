<template>
    <div >
        <strong>Чат с -     {{contact? contact.name : 'не выбран' }}


        </strong>



    </div>
</template>

<script>
    export default {
        props: ['contact'],
    }


</script>

<style  scoped>

    .startVideo{
        color: #fff;
        background-color: #d5b69fa6;
        border-color: #dc6d34;
    }





</style>

