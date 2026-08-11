const bosses = 
{ 
    deserto:{
        nome:"Rei das Dunas Eternas", habilidades:[ { nome:"Tempestade de Areia", tipo:"ataque_area", dano:20, alvos:3, efeito:"Reduz Precisão por 2 turnos", descricao:"Levanta uma enorme tempestade de areia." },
        { nome:"Presas das Areias", tipo:"ataque", dano:35, alvos:1, efeito:"aplica Sangramento",descricao:"Espinhos de areia endurecida surgem sob um alvo." },
        { nome:"Escorpião do Deserto", tipo:"ataque", dano:50, alvos:1, efeito:"aplica Veneno ",descricao:"Invoca uma cauda gigante de escorpião do deserto." },
        { nome:"Corpo de Areia", tipo:"ataque", dano:50, alvos:1, efeito:"ele se peida abruptamente", descricao:"Seu corpo instável dificulta ataques físicos." }, 
        { nome:"Sol Escaldante",tipo:"ataque", dano:10, alvos:3, duracao:3, recarga:4, efeito:"Todos recebem dano no final do turno", descricao:"O calor do deserto aumenta drasticamente." },
        { nome:"Enterrado nas Dunas", tipo:"ataque_area", dano:45, alvos:3, recarga:5, efeito:"ele te ataca abruptamente", descricao:"Mergulha na areia e volta com ataque surpresa." } ] },
    montanha:{ nome:"Pé-Grande", habilidades:[ 
        { nome:"Pisão Sísmico", tipo:"ataque_area", dano:30, alvos:3, efeito:"30% chance de Atordoamento por 1 turno", descricao:"Esmaga o chão com seu pé gigante." },
        { nome:"Arremesso de Rocha", tipo:"ataque", dano:55, alvos:1, efeito:"Ignora 50% da defesa", descricao:"Lança uma pedra gigante em um alvo." },
        { nome:"Golpe da Avalanche", tipo:"ataque_area", dano:25, alvos:3, efeito:"Reduz habilidades por 2 turnos", descricao:"Provoca uma avalanche." }, 
        { nome:"Pele de Granito", tipo:"ataque", dano:10, alvos:3, efeito:"Recebe 25% menos dano físico", descricao:"Sua pele fica extremamente resistente." },
        { nome:"Fúria das Alturas", tipo:"ataque", dano:25, alvos:2, efeito:"ele joga um granito em voce", descricao:"Entra em estado de fúria." }, 
        { nome:"Coração da Montanha", tipo:"ataque", dano:35, alvos:1, recarga:2, efeito:"ele liga o ar condicionado (ta muito gelado)", descricao:"Absorve energia das montanhas." } ] },
    floresta:{ nome:"Guardião da Floresta", habilidades:[
        { nome:"Chicote de Vinhas", tipo:"ataque", dano:40, alvos:1, efeito:"ele fança mt mal e seus olhos doem os olhos", descricao:"Vinhas atacam um inimigo." }, 
        { nome:"Investida do Carvalho", tipo:"ataque", dano:60, alvos:1, efeito:"ele te da um peteleco", descricao:"Avança com o peso de uma árvore." }, 
        { nome:"Esporos Tóxicos", tipo:"ataque_area", dano:20, alvos:3, efeito:"Aplica Veneno por 2 turnos", descricao:"Espalha veneno pela arena." }, 
        { nome:"Casca Milenar", tipo:"ataque", dano:15, alvos:3, efeito:"joga um galho em voce", descricao:"Sua casca fica mais resistente." }, 
        { nome:"Benção da Natureza", tipo:"ataque", dano:30, alvos:1, recarga:4, efeito:"Remove efeitos negativos", descricao:"As raízes restauram sua energia." }, 
        { nome:"Despertar da Floresta", tipo:"ataque", dano:70, alvos:1, duracao:3, recarga:5, efeito:"ele usa a ult da raze", descricao:"A floresta desperta ao redor do campo." } ] } 
};