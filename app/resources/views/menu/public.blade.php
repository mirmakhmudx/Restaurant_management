<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BitePlate Menu</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
<style>
*{-webkit-tap-highlight-color:transparent}
body{max-width:480px;margin:0 auto;background:#f3f4f6}
[x-cloak]{display:none!important}
</style>
</head>
<body class="min-h-screen pb-28">

<script>
window.__TABLE_ID = @json($table?->id);
window.__TABLE_NAME = @json($table ? "Stol ".$table->number : "Menu");
window.__ITEMS = @json($allItems);
</script>

<div id="biteplate" x-data="menuApp()">

  <!-- ORDER DONE -->
  <div x-show="orderDone" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center p-8 text-center">
    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6 text-5xl">&#x2705;</div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Buyurtma qabul qilindi!</h2>
    <p class="text-gray-500 mb-2">Raqam:</p>
    <p class="text-4xl font-bold font-mono mb-6" x-text="orderNum"></p>
    <p class="text-sm text-gray-400 mb-8">Ofitsiant tez keladi</p>
    <button x-on:click="orderDone=false" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-2xl">Yana buyurtma</button>
  </div>

  <!-- ITEM MODAL -->
  <div x-show="modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;z-index:40">
    <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.6);z-index:1" x-on:click="modal=null"></div>
    <div style="position:absolute;bottom:0;left:0;right:0;background:white;border-radius:1.5rem 1.5rem 0 0;max-height:85vh;overflow-y:auto;z-index:2">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f3f4f6">
        <div style="display:flex;align-items:center;gap:10px">
          <span x-text="modal ? modal.icon : ''" style="font-size:1.8rem"></span>
          <span x-text="modal ? modal.type : ''" style="font-size:12px;color:#9ca3af;font-weight:500"></span>
        </div>
        <button x-on:click="modal=null"
                style="width:32px;height:32px;background:#f3f4f6;border:none;border-radius:50%;font-size:16px;cursor:pointer">
          &#x2715;
        </button>
      </div>
      <div class="p-5">
        <div class="flex items-start justify-between gap-3 mb-2">
          <div class="flex-1">
            <h2 class="text-xl font-bold" x-text="modal ? modal.name : '' "></h2>
            <p class="text-sm text-gray-400 mt-1" x-text="modal ? modal.desc : '' "></p>
          </div>
          <p class="text-xl font-bold flex-shrink-0" x-text="modal ? '£'+modal.price.toFixed(2) : '' "></p>
        </div>
        <div class="flex gap-3 text-xs text-gray-400 mb-4">
          <span x-text="modal ? 'Tayyorlanish: '+modal.prep+'min' : '' "></span>
        </div>
        <!-- Modifiers -->
        <div x-show="modal && modal.mods && modal.mods.length > 0" class="mb-5">
          <p class="font-bold text-sm mb-2">Extra variantlar:</p>
          <div class="space-y-2">
            <template x-for="mod in (modal ? modal.mods : [])" :key="mod.id">
              <label class="flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer"
                     x-bind:class="hasMod(mod.id) ? 'border-gray-900 bg-gray-50' : 'border-gray-200'">
                <div class="flex items-center gap-3">
                  <div class="w-5 h-5 rounded border-2 flex items-center justify-center"
                       x-bind:class="hasMod(mod.id) ? 'bg-gray-900 border-gray-900' : 'border-gray-300'">
                    <span x-show="hasMod(mod.id)" class="text-white text-xs font-bold">&#x2713;</span>
                  </div>
                  <span class="font-medium text-sm" x-text="mod.name"></span>
                </div>
                <span class="text-sm font-bold text-green-600"
                      x-text="mod.price > 0 ? '+£'+mod.price.toFixed(2) : 'Bepul'"></span>
                <input type="checkbox" class="hidden" x-on:change="toggleMod(mod)">
              </label>
            </template>
          </div>
        </div>
        <div class="flex items-center justify-between mb-5">
          <p class="font-semibold">Miqdor:</p>
          <div class="flex items-center gap-4">
            <button x-on:click="qty=Math.max(1,qty-1)" class="w-10 h-10 rounded-full border-2 border-gray-900 flex items-center justify-center font-bold text-xl">&#8722;</button>
            <span class="text-xl font-bold w-6 text-center" x-text="qty"></span>
            <button x-on:click="qty++" class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-xl">+</button>
          </div>
        </div>
        <button x-on:click="addToCart()" class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl flex items-center justify-between px-5">
          <span x-text="qty+'x Savatga'"></span>
          <span x-text="'£'+modalTotal"></span>
        </button>
      </div>
    </div>
  </div>

  <!-- CART -->
  <div x-show="showCart" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;z-index:40">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.5)" x-on:click="showCart=false"></div>
    <div class="relative bg-white rounded-t-3xl">
      <div class="flex items-center justify-between px-5 py-4 border-b">
        <h3 class="font-bold text-lg">Buyurtma</h3>
        <button x-on:click="showCart=false" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center font-bold">&#x2715;</button>
      </div>
      <div class="max-h-64 overflow-y-auto px-5 py-3">
        <template x-for="item in cart" :key="item.key">
          <div class="flex items-center gap-3 py-3 border-b last:border-0">
            <span x-text="item.icon" class="text-xl"></span>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-sm" x-text="item.name"></p>
              <p class="text-xs text-gray-400" x-text="'£'+item.price.toFixed(2)"></p>
            </div>
            <div class="flex items-center gap-2">
              <button x-on:click="decCart(item.key)" class="w-7 h-7 rounded-full border-2 border-gray-300 flex items-center justify-center font-bold text-sm">&#8722;</button>
              <span class="w-4 text-center font-bold text-sm" x-text="item.qty"></span>
              <button x-on:click="incCart(item.key)" class="w-7 h-7 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-sm">+</button>
            </div>
            <span class="font-bold text-sm w-14 text-right" x-text="'£'+(item.price*item.qty).toFixed(2)"></span>
          </div>
        </template>
      </div>
      <div class="px-5 py-4 border-t">
        <div class="flex items-center justify-between mb-4">
          <span class="font-semibold text-gray-600">Jami:</span>
          <span class="text-2xl font-bold" x-text="totalStr"></span>
        </div>
        <button x-on:click="placeOrder()" x-bind:disabled="loading"
                class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl text-sm"
                x-bind:class="loading ? 'opacity-60' : ''">
          <span x-show="!loading">Buyurtma berish</span>
          <span x-show="loading" style="display:none">Yuborilmoqda...</span>
        </button>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <div class="bg-gray-900 text-white px-5 pt-8 pb-5">
    <p class="text-xs text-gray-400 mb-1">Xush kelibsiz</p>
    <h1 class="text-2xl font-bold" x-text="tableName"></h1>
    <p class="text-gray-400 text-sm mt-1" x-text="allItems.length + ' ta taom'"></p>
  </div>


  <!-- CATEGORY TABS -->
  <div class="bg-white border-b overflow-x-auto sticky top-0 z-10 shadow-sm">
    <div class="flex px-2 py-1 min-w-max">
      <template x-for="group in groups" :key="group.type">
        <a x-bind:href="'#group-'+group.type"
           class="flex-shrink-0 px-4 py-2.5 text-sm font-semibold text-gray-600 whitespace-nowrap hover:text-gray-900 border-b-2 border-transparent hover:border-gray-400 transition-all">
          <span x-text="group.icon + ' ' + group.label"></span>
        </a>
      </template>
    </div>
  </div>
  <!-- ITEMS -->
  <div class="px-4 py-5">
    <template x-for="group in groups" :key="group.type">
      <div class="mb-8" x-bind:id="'group-'+group.type">
        <div class="flex items-center gap-2 mb-4">
          <span x-text="group.icon" class="text-2xl"></span>
          <h2 class="font-bold text-gray-900 text-lg" x-text="group.label"></h2>
        </div>
        <div class="space-y-3">
          <template x-for="item in group.items" :key="item.id">
            <div x-on:click="item.available && openItem(item)"
                 class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm cursor-pointer hover:shadow-md transition-shadow"
                 x-bind:class="!item.available ? 'opacity-60 cursor-not-allowed' : '' ">
              <div class="flex">
                <div class="w-28 h-28 flex-shrink-0 bg-gray-100 overflow-hidden">
                  <img x-show="item.image" x-bind:src="item.image" class="w-full h-full object-cover">
                  <div x-show="!item.image" class="w-full h-full flex items-center justify-center">
                    <span x-text="item.icon" class="text-5xl opacity-20"></span>
                  </div>
                </div>
                <div class="flex-1 p-3 flex flex-col min-w-0">
                  <p class="font-bold text-gray-900" x-text="item.name"></p>
                  <p class="text-xs text-gray-400 mt-0.5 line-clamp-2" x-text="item.desc"></p>
                  <p class="text-xs text-gray-400 mt-1" x-text="'&#9201; '+item.prep+'min'"></p>
                  <div class="flex items-center justify-between mt-auto pt-2">
                    <span class="font-bold text-gray-900" x-text="'£'+item.price.toFixed(2)"></span>
                    <span x-show="item.mods && item.mods.length > 0" class="text-xs text-blue-500"
                          x-text="'+ '+item.mods.length+' extra'"></span>
                    <span x-show="!item.available" class="text-xs text-gray-400">Tugagan</span>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </template>
    <p class="text-center py-4 text-xs text-gray-400">BitePlate Restaurant</p>
  </div>

  <!-- CART BAR -->
  <div x-show="totalItems > 0" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:30;padding:0 16px 20px">
    <button x-on:click="showCart=true"
            class="w-full flex items-center justify-between bg-gray-900 text-white rounded-2xl px-5 py-4 shadow-2xl" style="max-width:480px;margin:0 auto">
      <div class="flex items-center gap-2">
        <span class="w-7 h-7 bg-white rounded-full flex items-center justify-center text-gray-900 text-xs font-bold"
              x-text="totalItems"></span>
        <span class="font-bold">Buyurtmani ko'rish</span>
      </div>
      <span class="font-bold" x-text="totalStr"></span>
    </button>
  </div>

</div>

<script>
document.addEventListener('alpine:init', () => { Alpine.data('menuApp', menuApp); });
function menuApp() {
  const raw = window.__ITEMS || [];
  const types = [...new Set(raw.map(i=>i.type))];
  const iconMap = {};
  raw.forEach(i=>{ if(!iconMap[i.type]) iconMap[i.type]=i.icon; });

  return {
    allItems: raw,
    tableName: window.__TABLE_NAME || "Menu",
    groups: types.map(t=>({
      type: t,
      label: t,
      icon: iconMap[t] || "&#127869;",
      items: raw.filter(i=>i.type===t)
    })),
    cart: [],
    modal: null,
    showCart: false,
    orderDone: false,
    orderNum: "",
    loading: false,
    qty: 1,
    selectedMods: [],

    openItem(item) {
      this.modal = item;
      this.qty = 1;
      this.selectedMods = [];
    },
    toggleMod(mod) {
      const i = this.selectedMods.findIndex(m=>m.id===mod.id);
      if(i>=0) this.selectedMods.splice(i,1);
      else this.selectedMods.push(mod);
    },
    hasMod(id) { return this.selectedMods.some(m=>m.id===id); },

    get modalTotal() {
      if(!this.modal) return "0.00";
      const s = this.selectedMods.reduce((a,m)=>a+m.price,0);
      return ((this.modal.price + s) * this.qty).toFixed(2);
    },

    addToCart() {
      if(!this.modal) return;
      const s = this.selectedMods.reduce((a,m)=>a+m.price,0);
      const key = this.modal.id+"_"+this.selectedMods.map(m=>m.id).sort().join(",");
      const ex = this.cart.find(i=>i.key===key);
      if(ex) { ex.qty += this.qty; }
      else {
        this.cart.push({
          key, id:this.modal.id, name:this.modal.name,
          icon:this.modal.icon,
          price: this.modal.price + s,
          mods: [...this.selectedMods],
          qty: this.qty
        });
      }
      this.modal = null;
    },

    get totalItems() { return this.cart.reduce((s,i)=>s+i.qty, 0); },
    get totalPrice() { return this.cart.reduce((s,i)=>s+(i.price*i.qty), 0); },
    get totalStr()   { return "£"+this.totalPrice.toFixed(2); },

    decCart(key) {
      const f = this.cart.find(i=>i.key===key);
      if(!f) return;
      if(f.qty>1) f.qty--; else this.cart = this.cart.filter(i=>i.key!==key);
    },
    incCart(key) {
      const f = this.cart.find(i=>i.key===key);
      if(f) f.qty++;
    },

    async placeOrder() {
      if(!this.cart.length || this.loading) return;
      this.loading = true;
      try {
        const r = await fetch("/public-menu/order", {
          method: "POST",
          headers: {"Content-Type":"application/json","Accept":"application/json"},
          body: JSON.stringify({
            table_id: window.__TABLE_ID,
            items: this.cart.map(i=>({
              id: i.id,
              quantity: i.qty,
              notes: i.mods.map(m=>m.name).join(", ")
            }))
          })
        });
        const d = await r.json();
        if(d.success) {
          this.orderNum  = d.order_number;
          this.orderDone = true;
          this.showCart  = false;
          this.cart      = [];
        } else {
          alert(d.message || "Xatolik");
        }
      } catch(e) { alert("Ulanish xatoligi"); }
      this.loading = false;
    }
  };
}
</script>

</body>
</html>
