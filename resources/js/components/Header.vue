<template>
    <div >
        <strong>Чат с - {{contact? contact.name : 'не выбран' }}</strong>
    </div>
</template>

<script>
    export default {
        props: ['contact'],
    }
</script>


