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
    font-size: 25px;
    border-radius: 15px;    
    color: red;
    font-weight: 700;
    transition: all .3s;
}

.button:hover{
    box-shadow: 0px 0px 12px -7px #000;
}
</style>
