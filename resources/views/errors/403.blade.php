<x-errors.minimal
    code="403"
    title="Você não tem permissão"
    :message="$exception->getMessage() ?: 'Seu perfil não libera acesso a essa área do sistema.'"
    tone="amber"
    icon="ph-lock-key"
/>
