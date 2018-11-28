<template>
 <div class="share-program">

  <!-- <h1>Teile dein Programm</h1> -->
  <div>
   <div class="share-link">
    <p><strong>Personalisiere dein Programm, bevor du es teilst.</strong></p>
    <input @change="getItems" v-model="name" placeholder="Name"><br><br>


  </div>
  <div class="share">
    <!-- <h1>Auf Social-Media teilen</h1> -->
    <printbutton></printbutton>

    <div v-on:click="getItems"><button><a :href="mail"><i>ⓜ</i> Mail</a></button></div>
    <div v-on:click="getItems"><button><a :href="facebook"><i>ⓕ</i> Facebook</a></button></div>
    <div v-on:click="getItems"><button><a :href="twitter"><i>ⓣ</i> Twitter</a></button></div>
  </div><br>
  <div class="share-link">

   <div>
    <div><a :href="url"><nobr>{{ url }}</nobr></a></div>
    <div class="share-button" v-on:click="getItems"><span  v-clipboard:copy="url" v-clipboard:success="onCopy" v-clipboard:error="onError">{{ buttonText }}</span></div>
  </div>
</div>


</div>
</div>
</template>

<script>
import printButton from './printButton.vue'


module.exports = {
 name: 'shareprogram',
 props: ['programevent'],
 components: {
  printbutton: printButton

},

data: function () {
 return {
   mail: '',
   url: '',
   savedItems: [],
   name: '',
   message: 'Copy These Text',
   buttonText: 'Link kopieren',
     // saved: false,
     facebook: '',
     // twitter: 'https://twitter.com/home?status=Mein Programm für die Museumsnacht Basel am 21. Januar 2018: ',
     twitter: '',
     google: '',


   }
 },
 ready:function(){
  foo();
},
mounted () {
 this.getItems();
},
methods: {
 getItems: function (get) {
  var idListFromLocalStorage = JSON.parse(localStorage.getItem('programId'));
  this.savedItems = idListFromLocalStorage;
  var savedList = JSON.stringify(idListFromLocalStorage);
  var savedListAsString = idListFromLocalStorage.join('+');
  var savedListAsStringForSM = idListFromLocalStorage.join('%2B');


  var name = this.name;
  var url = "https://museumsnacht.ch/programm/vorschlag?ids=" + savedListAsString + "&name=" + name;
  var urlSm = "https://museumsnacht.ch/programm/vorschlag?ids=" + savedListAsStringForSM;

  this.url = url;
  var myProgram = this.programevent;
  var titles = []
  var IDs = []
  var length = myProgram.length;   
  for (var i = 0; i < length; i++) {
   var item = myProgram[i]
   var itemId = item.id;
   var hit = savedList.indexOf(itemId)
   if (hit > -1) {
    var result = item.title + " im " + item.parent.title + "%0D%0A";
    var resultIds = item.id;
    titles.push(result);
  }
}
if (get) {
 // this.mail = 'mailto:'+'?subject=Mein Programm für die Museumsnacht Basel&body=Das ist mein Programm für die Museumsnacht Basel am 21. Juni 2018: %0D%0A' + titles + '%0D%0A Link zur Auswahl: ' + url;
 this.mail = 'mailto:'+'?subject=Museumsnacht Basel - Mein Programm&body=Hier findest du meine ausgewählten Programmangebote für die Museumsnacht Basel am 19. Januar 2018: ' + urlSm + '%26name=' + this.name;
 var foo = "&name="
 // this.twitter = 'https://twitter.com/home?status=Mein Programm für die Museumsnacht Basel am 19. Januar 2018: ' + urlSm + '%26name=' + this.name;
 this.twitter = 'https://twitter.com/intent/tweet?text=Mein Programm für die Museumsnacht Basel am 19. Januar 2018: ' + urlSm + '%26name=' + this.name;
 
 this.facebook = 'https://www.facebook.com/sharer/sharer.php?u=' + urlSm + '%26name=' + this.name;
}
},
onCopy: function (e) {
  this.buttonText = "✔︎ Link ist kopiert.";
      // this.saved = !this.saved;
    },
    onError: function (e) {
      this.buttonText = "Deine Auswahl konnte leider nicht in der Zwischenablage gespeichert werden";
    }

  }
}

</script>

<style lang="css" scoped>
</style>