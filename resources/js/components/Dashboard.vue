<template>
    <div class="wrapper">
        <div class="sidebar d-flex flex-column">
            <div class="sidebar-header">
                <div class="dropdown show">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Welcome {{ userName}}
                    </a>
                </div>
            </div>
            <div class="sidebar-content">
                <Contacts :contacts="contacts" @selected-contact="openChat" />
            </div>            
        </div>
        <Chat :contact="selectedContact" :messages="messages" @new-message="pushMessage" />
    </div>    
</template>

<script>
    import Contacts from './parts/Contacts';
    import Chat from './parts/Chat';

    export default{
        // props:{
        //     user:{
        //         type: Object,
        //         required: true
        //     },
        //     logoutRoute:{
        //         type: String,
        //         required: true
        //     }
        // },
        data(){
            return {
                contacts: [],
                messages: [],
                selectedContact: null,
                token: '',
                userName:null,
                userId:'',
            }
        },
        mounted() {
            this.token = localStorage.getItem('token');
            Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer '+this.token;
           axios.get('/contacts?token=' + this.$store.state.token)
                .then( (response) => {
                    this.contacts = response.data;                                   
                })
                

            
        },
        created() {
            axios.get('/info/user?token=' + this.$store.state.token)
                .then( (response) => {
                    this.userName = response.data.user.name;
                    this.userId = response.data.user.id;
                    Echo.private(`chanel.messages.`+this.userId)
                    .listen('SendMessage', (e) => {                    
                    this.incomingMessage(e.message);                    
            });                                     
                });
            
        },
        methods:{
            openChat(contact){ 
                this.updateContactList(contact, '', true);
                axios.get(`/messages/${contact.id}?token=`+ this.$store.state.token)
                    .then( (response) => {
                        this.messages = response.data;  
                        this.selectedContact = contact;  
                    });               
            },
            pushMessage(message){                     
                this.messages.push(message);               
            },
            updateContactList(selectedContact, message, reset){
                var index = this.contacts.findIndex(contact => contact.id === selectedContact.id);                
                if(reset){
                    this.contacts[index].unread = 0;   
                } else {
                    this.contacts[index].unread++; 
                    this.playSound();
                }

                if(message){
                    this.contacts[index].latest_message = message;   
                }
                
            },
            incomingMessage(message){               
                if(this.selectedContact && this.selectedContact.id == message.from_id){
                    this.pushMessage(message);                   
                    this.updateContactList(message.user, message.message, true); 
                    axios.get(`/read/${message.from_id}?token=`+ this.$store.state.token); 
                    return;    
                }  
                this.updateContactList(message.user, message.message, false);
            },
            playSound(){
                var audio = new Audio('/audio/Bing-sound.mp3');
                audio.play();
            },
            logout(e){               
                e.preventDefault();
                this.$refs.logoutForm.submit();
            }
        },
        components: {Contacts, Chat}
    }
</script>