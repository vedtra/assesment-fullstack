<template>
    <div class="chat-content-inner">
        <div v-for="message in messages" :class="`d-flex mb-4${contact.id == message.from_id ? ' justify-content-start' : ' justify-content-end'}`" :key="message.id">
            <div class="avatar">
                <img :src="`https://i.pravatar.cc/50?img=${message.from_id}`" />
            </div>
            <div class="message-details">                             
                <span class="message">{{message.message}}</span>
                <span class="time">{{ message.created_at | moment }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props: {
            contact: {
                type: Object
            },
            messages: {
                type: Array,
                required: true
            }
        },
        filters:{
            moment: function(date){               
                return moment(date).format('MMM DD, YYYY h:mm');                
            }
        }
    }    
</script>