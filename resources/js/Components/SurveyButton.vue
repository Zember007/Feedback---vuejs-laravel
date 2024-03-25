<template>
    <button
        class="button"
    >
        <slot />
    </button>
</template>

<style scoped>
.button{
    padding: 5px 30px;
    background: white;
    border-radius: 15px;    
    color: #156CB0;
    font-weight: 700;
    transition: all .3s;
    max-width: 250px;
    height: 50px;
    font-size:18px;
}

.button:hover{
    box-shadow: 0px 0px 12px -7px #000;
}
</style>
