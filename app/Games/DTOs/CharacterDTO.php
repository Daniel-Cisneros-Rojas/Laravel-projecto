<?php

namespace App\Games\DTOs;

class CharacterDTO
{
    public function __construct(
        public int $id,
        public string $hanzi,
        public string $pinyin,
        public ?string $meaning = null,
        public int $level = 1,
        public ?string $image = null,
        public ?string $audio = null,
        public ?string $example_sentence = null,
    ) {}

    /**
     * Crear DTO desde un modelo
     */
    public static function fromModel(\App\Models\Character $character): self
    {
        return new self(
            id: $character->id,
            hanzi: $character->hanzi,
            pinyin: $character->pinyin,
            meaning: $character->meaning,
            level: $character->level,
            image: $character->image,
            audio: $character->audio,
            example_sentence: $character->example_sentence,
        );
    }

    /**
     * Convertir a array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'hanzi' => $this->hanzi,
            'pinyin' => $this->pinyin,
            'meaning' => $this->meaning,
            'level' => $this->level,
            'image' => $this->image,
            'audio' => $this->audio,
            'example_sentence' => $this->example_sentence,
        ];
    }
}
